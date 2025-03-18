<?php

class ApiClient {
    private $apiUrl;
    private $token;

    /**
     * Constructor to initialize the API client.
     */
    public function __construct() {
        $this->apiUrl = getenv( 'API_URL' );
        $this->authenticate();
    }

    /**
     * Authenticates with the API and retrieves the Bearer Token.
     *
     * @throws Exception If authentication fails.
     */
    private function authenticate() {
        // TODO: Implement the authenticate method
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
    private function sendRequest( $method, $url, $data = null ) {
        // TODO: Implement the sendRequest method
    }
}
