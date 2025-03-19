<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/EventController.php';

// Check if the .env file was loaded correctly
if ( ! getenv( 'API_URL' ) ) {
    die( 'Error: Environment variables not configured!' );
}

// Set the HTTP response header to indicate that we are using JSON
header( 'Content-Type: application/json' );

$event = new EventController();

$method  = $_SERVER[ 'REQUEST_METHOD' ];
$request = $_SERVER[ 'REQUEST_URI' ];

// remove any query string from the request
$request = explode( '?', $request )[ 0 ];
switch ( true ) {
    case $request === '/api/events' && $method === 'GET':
        echo json_encode( $event->getEvents() );
        break;

    case $request === '/api/event' && $method === 'POST':
        echo json_encode( $event->createEvent() );
        break;

    case $request === '/api/event/detail' && $method === 'GET':
        if ( ! isset( $_GET[ 'id' ] ) ) {
            http_response_code( 400 );
            echo json_encode( ['error' => 'ID is required!'] );
            break;
        }

        $id = $_GET[ 'id' ];
        echo json_encode( $event->getEventDetail( $id ) );
        break;

    default:
        // Return 404 error if the route does not exist
        http_response_code( 404 );
        echo json_encode( [ 'message' => 'Route not found' ] );
        break;
}