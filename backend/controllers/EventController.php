<?php

require_once __DIR__ . '/../models/Event.php';

class EventController {

    public static function getEvents() {
        $event = new Event();
        return $event->getAll();
    }

    public static function createEvent() {
        $event = new Event();
        $data  = json_decode( file_get_contents( "php://input" ), true );

        if ( ! $data[ 'title' ] || ! $data[ 'start_date' ] || ! $data[ 'end_date' ] ) {
            http_response_code( 400 );
            return [ 'error' => 'Title, start_date, and end_date are required!' ];
        }

        return $event->create( $data );
    }

    public static function getEventDetail( $id ) {
        if ( ! $id ) {
            http_response_code( 400 );
            return [ 'error' => 'ID is required!' ];
        }

        $event = new Event();
        return $event->findById( $id );
    }
}
