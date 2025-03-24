<?php

class ApiClient {
    private $apiUrl;
    private $token;
    private $tokenExpiration;

    private $cacheFile = __DIR__ . '/../cache/token_cache.json';

    /**
     * Constructor to initialize the API client.
     */
    public function __construct() {
        $this->apiUrl = getenv( 'API_URL' ) . getenv( 'API_CLIENT_ID' );
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
            $response = $this->sendRequest('POST', '/api/Auth', [
                'clientId'     => getenv( 'API_CLIENT_ID' ),
                'clientSecret' => getenv( 'API_CLIENT_SECRET' )
            ]);

            if ( isset( $response[ 'access_token' ] ) && ! empty( $response[ 'access_token' ] ) ) {
                $this->token = $response[ 'access_token' ];

                $this->tokenExpiration = time() + $response[ 'expires_in' ];

                // Cache the token and expiration time
                file_put_contents( $this->cacheFile, json_encode( [
                    'access_token' => $this->token,
                    'expires_in'   => $this->tokenExpiration
                ] ) );
            } else {
                throw new Exception( "Invalid response: Missing access_token." );
            }
        } catch ( Exception $e ) {
            throw new Exception( "Authentication failed: " . $e->getMessage() );
        }
    }

    /**
     * Sends HTTP requests to the API.
     *
     * @param string $method HTTP method (GET, POST, etc.).
     * @param string $url API endpoint URL.
     * @param array|null $data Request payload (optional).
     * @return array Decoded JSON response.
     *
     * @throws Exception If the request fails.
     */
    public function sendRequest( $method, $url, $data = [] ) {
        $endUrl = "{$this->apiUrl}{$url}";

        $headers = [
            'Content-Type: application/json',
            'accept: text/json'
        ];

        if ( $this->token ) {
            $headers[] = "Authorization: Bearer {$this->token}";
        }

        $options = [
            CURLOPT_URL            => $endUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
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

        if ( $error ) {
            throw new Exception( "Request error: $error" );
        }

        if ( $httpCode < 200 || $httpCode >= 300 ) {
            throw new Exception( "API responded with HTTP code $httpCode: $response" );
        }

        $decodedResponse = json_decode( $response, true );

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            throw new Exception( "Failed to decode JSON response: " . json_last_error_msg() );
        }

        return $decodedResponse;
    }
}
