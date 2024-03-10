<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Http\Controllers\EventsController;

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


    public static function whereCategoryIn($category): array
    {
        // Récupérer tous les événements en utilisant la méthode getEvents du contrôleur EventsController
        $events = static::getEvents(new Request());

        // Filtrer les événements qui contiennent la catégorie spécifiée
        $filteredEvents = [];
        foreach ($events as $event) {
            // Vérifier si la catégorie spécifiée est présente dans les catégories de l'événement
            if (in_array($category, $event->getCategories())) {
                $filteredEvents[] = $event;
            }
        }

        return $filteredEvents;
    }

    public function getCategories(): array
    {
        // Vérifier si la description est une chaîne de caractères
        if (is_string($this->description)) {
            // Retourner un tableau des catégories en séparant la description par des virgules
            return explode(',', $this->description);
        } else {
            // Retourner un tableau vide si la description n'est pas une chaîne de caractères
            return [];
        }
    }


    public static function getEvents(Request $request): array
    {
        // Appel à la méthode getEvents de EventsController pour récupérer les événements depuis Google Calendar
        $eventsController = new EventsController();
        $response = $eventsController->getEvents($request);

        // Récupérer les événements depuis la réponse JSON
        $events = $response->getOriginalContent()['events'];

        return $events;
    }

    public static function getCategoriesById(Request $request, $eventId): array
    {
        // Appel à la méthode getCategoriesById de EventsController pour récupérer les catégories de l'événement spécifié par son ID
        $eventsController = new EventsController();
        $response = $eventsController->getCategoriesById($request, $eventId);

        // Récupérer les catégories depuis la réponse JSON
        $categories = $response->getOriginalContent()['categories'];

        return $categories;
    }

}
