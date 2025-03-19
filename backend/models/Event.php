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
        $data = [
            'id'          => uniqid(),
            'title'       => $data[ 'title' ],
            'description' => $data[ 'description' ],
            'startDate'   => $data[ 'start_date' ],
            'endDate'     => $data[ 'end_date' ]
        ];

        $response = $this->apiClient->sendRequest( 'POST', '/api/Events', $data );
        return $response;
    }

    public function findById( $id ) {
        $response = $this->apiClient->sendRequest( 'GET', "/api/Events/{$id}" );
        return $response;
    }
}
