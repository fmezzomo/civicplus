<?php

require_once __DIR__ . '/../vendor/autoload.php';
// Autoload do Composer
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ApiClient
{
    private $apiUrl;
    private $token;
    private $tokenExpiration;
    private $logger;

    private $cacheFile = __DIR__ . '/../cache/token_cache.json';


    /**
     * Constructor to initialize the API client.
     */
    public function __construct()
    {
        $this->apiUrl = getenv( 'API_URL' ) . getenv( 'API_CLIENT_ID' );
        $this->logger = new Logger( 'ApiClient' );
        $this->logger->pushHandler( new StreamHandler( __DIR__ . '/../logs/app.log', Logger::ERROR ) );
        $this->loadToken();
    }

    /**
     * Loads the token from cache or authenticates if the token is missing or expired.
     */
    private function loadToken() {
        // Check if a cached token exists and is still valid
        if ( file_exists( $this->cacheFile ) ) {
            $cache = json_decode( file_get_contents( $this->cacheFile ), true );

            if ( isset( $cache[ 'access_token' ], $cache[ 'expires_in' ] ) && time() < $cache[ 'expires_in' ] ) {
                $this->token           = $cache[ 'access_token' ];
                $this->tokenExpiration = $cache[ 'expires_in' ];
                return;
            }
        }

        // Authenticate and get a new token if no valid token is found
        $this->authenticate();
    }


    /**
     * Authenticates with the API and retrieves the Bearer Token.
     *
     * @throws Exception If authentication fails.
     */
    private function authenticate() {
        try {
            $response = $this->sendRequest(
                'POST',
                '/api/Auth',
                [
                    'clientId'     => getenv( 'API_CLIENT_ID' ),
                    'clientSecret' => getenv( 'API_CLIENT_SECRET' ),
                ]
            );

            if ( $response[ 'success' ] && isset( $response[ 'data' ][ 'access_token' ] ) ) {
                $this->token           = $response[ 'data' ][ 'access_token' ];
                $this->tokenExpiration = time() + $response[ 'data' ][ 'expires_in' ];

                // Cache the token and expiration time
                file_put_contents(
                    $this->cacheFile,
                    json_encode( [
                        'access_token' => $this->token,
                        'expires_in'   => $this->tokenExpiration,
                    ] )
                );
            } else {
                $this->logger->error( 'Invalid response: Missing access_token.' );
                return [
                    'success' => false,
                    'message' => 'Invalid response: Missing access_token.',
                ];
            }
        } catch ( Exception $e ) {
            $this->logger->error( 'Authentication failed: ' . $e->getMessage() );
            return [
                'success' => false,
                'message' => 'Authentication failed: ' . $e->getMessage(),
            ];
        }

        return [
            'success' => true,
            'message' => 'Authentication successful.',
        ];
    }


    /**
     * Sends HTTP requests to the API.
     *
     * @param  string     $method HTTP method (GET, POST, etc.).
     * @param  string     $url    API endpoint URL.
     * @param  array|null $data   Request payload (optional).
     * @return array Response to be sent to the frontend.
     */
    public function sendRequest( $method, $url, $data = [] ) {
        // Validate HTTP method
        $validMethods = [
            'GET',
            'POST',
            'PUT',
            'DELETE',
        ];
        if ( ! in_array( strtoupper( $method ), $validMethods ) ) {
            $this->logger->error( "Invalid HTTP method: $method" );
            return [
                'success' => false,
                'message' => "Invalid HTTP method: $method",
            ];
        }

        $endUrl = "{$this->apiUrl}{$url}";

        $headers = [
            'Content-Type: application/json',
            'accept: text/json',
        ];

        if ( $this->token ) {
            $headers[] = "Authorization: Bearer {$this->token}";
        }

        $options = [
            CURLOPT_URL            => $endUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => strtoupper( $method ),
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => getenv( 'API_TIMEOUT' ) ?: 30,
        ];

        if ( $data ) {
            $options[ CURLOPT_POSTFIELDS ] = json_encode( $data );
        }

        $ch = curl_init();
        curl_setopt_array( $ch, $options );
        $response = curl_exec( $ch );
        $error    = curl_error( $ch );
        $httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        curl_close( $ch );

        // Handle cURL errors
        if ( $error ) {
            return $this->handleError( "Request error: $error", $httpCode );
        }

        // Log the full response for debugging
        $this->logger->debug(
            "Full API response: $response",
            [ 'httpCode' => $httpCode ]
        );

        // Decode the JSON response
        $decodedResponse = $this->decodeJsonResponse( $response, $httpCode );
        if ( ! $decodedResponse[ 'success' ] ) {
            return $decodedResponse;
        }

        // Handle HTTP errors and detailed API error messages
        if ( $httpCode < 200 ||
             $httpCode >= 300 ||
             ( isset( $decodedResponse[ 'data' ][ 'statusCode' ] ) &&
             $decodedResponse[ 'data' ][ 'statusCode' ] >= 400 ) ) {

            $errorMessage = ( $decodedResponse[ 'data' ][ 'message' ] ?? 'Unknown error');
            $statusCode   = ( $decodedResponse[ 'data' ][ 'statusCode' ] ?? $httpCode);

            return $this->handleError( "API error: $errorMessage", $statusCode, $decodedResponse[ 'data' ] );
        }

        return [
            'success' => true,
            'data'    => $decodedResponse[ 'data' ],
        ];
    }


    /**
     * Decodes a JSON response and handles errors.
     *
     * @param  string  $response The raw response string.
     * @param  integer $httpCode The HTTP status code.
     * @return array Decoded response or error details.
     */
    private function decodeJsonResponse( $response, $httpCode ) {
        $decodedResponse = json_decode( $response, true );

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            // Attempt to extract JSON part from the response
            $jsonStart = strpos( $response, '{' );
            if ( $jsonStart !== false ) {
                $jsonPart        = substr( $response, $jsonStart );
                $decodedResponse = json_decode( $jsonPart, true );

                if ( json_last_error() === JSON_ERROR_NONE ) {
                    $this->logger->warning(
                        'Non-JSON content detected before JSON part in response.',
                        [
                            'nonJsonContent' => substr( $response, 0, $jsonStart ),
                            'jsonPart'       => $jsonPart,
                        ]
                    );
                    return [
                        'success' => true,
                        'data'    => $decodedResponse,
                    ];
                }
            }

            $this->logger->error(
                'Failed to decode JSON response: ' . json_last_error_msg(),
                [
                    'response' => $response,
                    'httpCode' => $httpCode,
                ]
            );
            return [
                'success'  => false,
                'message'  => 'Failed to decode JSON response: ' . json_last_error_msg(),
                'httpCode' => $httpCode,
            ];
        }

        return [
            'success' => true,
            'data'    => $decodedResponse,
        ];
    }


    /**
     * Handles errors by logging and returning a standardized response.
     *
     * @param  string     $message  The error message.
     * @param  integer    $httpCode The HTTP status code.
     * @param  array|null $details  Additional error details (optional).
     * @return array Standardized error response.
     */
    private function handleError( $message, $httpCode, $details = null ) {
        $this->logger->error(
            $message,
            [
                'httpCode' => $httpCode,
                'details'  => $details,
            ]
        );

        return [
            'success'  => false,
            'message'  => $message,
            'httpCode' => $httpCode,
            'details'  => $details,
        ];
    }
}
