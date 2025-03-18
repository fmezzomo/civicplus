<?php

/**
 * Load environment variables from a .env file.
 *
 * @param string $path Path to the .env file.
 *
 * @throws Exception If the .env file is not found.
 */
function loadEnv( $path )
{
    if ( ! file_exists( $path ) ) {
        throw new Exception( ".env file not found!" );
    }

    $lines = file( $path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
    foreach ( $lines as $line ) {
        if ( strpos( trim( $line ), '#' ) === 0 ) {
            continue;
        }

        list( $key, $value ) = explode( '=', $line, 2 );
        putenv( "$key=$value" );
        $_ENV[ $key ] = $value;
    }
}

loadEnv( __DIR__ . '/.env' );
