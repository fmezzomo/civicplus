<?php

require_once __DIR__ . '/../models/Event.php';

class EventController {

    public static function getEvents() {
        $event = new Event();
        return $event->getAll();
    }

    public static function createEvent() {
        $event = new Event();
        $data = [
            'title'       => 'Test Event',
            'description' => 'Crhistmas event',
            'start_date'  => '2025-12-25T00:00:00Z',
            'end_date'    => '2025-12-25T23:59:59Z'
        ];

        return $event->create( $data );
    }

    public static function getEventDetail($id) {
        // TODO: Implement the getEventDetail method
    }
}
