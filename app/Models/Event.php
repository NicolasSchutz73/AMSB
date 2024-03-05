<?php

namespace App\Models;

class Event {
    public $id;
    public $title;
    public $description;
    public $location;
    public $start;
    public $end;
    public $attendees;
    public $isRecurring;

    public function __construct($id, $title, $start, $end, $description = null, $location = null, $attendees = null, $isRecurring = false) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
        $this->start = $start;
        $this->end = $end;
        $this->attendees = $attendees;
        $this->isRecurring = $isRecurring;
    }

    public function getCategories(): array
    {
        // Retourner un tableau des catégories en séparant la description par des virgules
        return explode(',', $this->description);
    }
}
