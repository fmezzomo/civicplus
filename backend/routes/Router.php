<?php

class Router {
    private $routes = [];

    public function addRoute( $method, $path, $callback ) {
        $this->routes[] = [
            'method'   => strtoupper( $method ),
            'path'     => $path,
            'callback' => $callback,
        ];
    }

    public function dispatch( $method, $requestUri ) {
        $requestUri = explode( '?', $requestUri )[0]; // Remove query string
        foreach ( $this->routes as $route ) {
            if ( $route[ 'method' ] === strtoupper( $method ) && $route[ 'path' ] === $requestUri ) {
                return call_user_func( $route[ 'callback' ] );
            }
        }

        // Return 404 if no route matches
        http_response_code( 404 );
        return json_encode( [ 'message' => 'Route not found' ] );
    }
}
