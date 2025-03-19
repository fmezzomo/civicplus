<?php

require_once __DIR__ . '/../models/Event.php';

class EventController {

    public static function getEvents() {
        $event = new Event();
        return $event->getAll();
    }

    public static function createEvent() {
        // TODO: Implement the createEvent method
    }

    public static function getEventDetail($id) {
        // TODO: Implement the getEventDetail method
    }
}
