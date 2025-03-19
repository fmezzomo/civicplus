<?php

require_once __DIR__ . '/../services/ApiClient.php';

class Event {
    private $apiClient;

    public function __construct() {
       $this->apiClient = new ApiClient();
    }

    public function getAll() {
        $queryParams = [
            '$top'     => 20,
            '$skip'    => 0,
            '$orderby' => 'startDate desc',
        ];
    
        $queryString = http_build_query( $queryParams );
        $queryString = str_replace('+', '%20', $queryString);
        $url         = "/api/Events?" . $queryString;

        $response = $this->apiClient->sendRequest( 'GET', $url );
        return $response;
    }

    public function create( $data ) {
        // TODO: Implement the create method
    }

    public function findById( $id ) {
        // TODO: Implement the findById method
    }
}
