<?php

namespace App\Models;

namespace App\Models;

class Event {
    public $id;
    public $title;
    public $description;
    public $location;
    public $start;
    public $end;
    public $attendees;

    public function __construct($id, $title, $start, $end, $description = null, $location = null, $attendees = null) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
        $this->start = $start;
        $this->end = $end;
        $this->attendees = $attendees;
    }
}
