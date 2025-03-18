<?php

require_once __DIR__ . '/../config/config.php';

// Check if the .env file was loaded correctly
if ( ! getenv( 'API_URL' ) ) {
    die( 'Error: Environment variables not configured!' );
}

session_start();

// Set the HTTP response header to indicate that we are using JSON
header( 'Content-Type: application/json' );

$method  = $_SERVER[ 'REQUEST_METHOD' ];
$request = $_SERVER[ 'REQUEST_URI' ];
switch ( true ) {
    case $request === '/api/events' && $method === 'GET':
        // TODO: Implement the getEvents
        break;
    case $request === '/api/event' && $method === 'POST':
        // TODO: Implement the createEvent
        break;
    case $request === '/api/event/detail' && $method === 'GET':
        // TODO: Implement the getEventDetail
        break;
    default:
        // Return 404 error if the route does not exist
        http_response_code( 404 );
        echo json_encode( [ 'message' => 'Route not found' ] );
        break;
}