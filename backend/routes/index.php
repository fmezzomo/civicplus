<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/../controllers/EventController.php';

$frontendUrl = getenv( 'FRONTEND_URL' );
if ( ! $frontendUrl ) {
    die( 'Error: FRONTEND_URL is not configured in the environment variables!' );
}

header( "Access-Control-Allow-Origin: $frontendUrl" );
header( "Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS" );
header( "Access-Control-Allow-Headers: Content-Type, Authorization" );

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'OPTIONS' ) {
    http_response_code( 200 );
    exit;
}

header( 'Content-Type: application/json' );

// Initialize the router
$router = new Router();

// Define routes
$router->addRoute( 'GET', '/api/events', function () {
    $event = new EventController();
    return json_encode($event->getEvents());
} );

$router->addRoute( 'POST', '/api/event', function () {
    $event = new EventController();
    return json_encode( $event->createEvent() );
} );

$router->addRoute( 'GET', '/api/event/detail', function () {
    if ( ! isset( $_GET[ 'id' ] ) ) {
        http_response_code( 400 );
        return json_encode( [ 'error' => 'ID is required!' ] );
    }
    $id = $_GET[ 'id' ];
    $event = new EventController();
    return json_encode( $event->getEventDetail( $id ) );
});

// Dispatch the request
echo $router->dispatch ($_SERVER[ 'REQUEST_METHOD' ], $_SERVER[ 'REQUEST_URI' ] );