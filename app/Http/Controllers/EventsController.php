<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Google_Client;
use Google_Service_Calendar;
use App\Models\Event; // Importez la classe Event

class EventsController extends Controller
{



    public function getEvents(Request $request): JsonResponse
    {
        // Configuration de l'accès à l'API Google Calendar
        $client = new Google_Client();
        $client->setAuthConfig(config_path('google/credentials.json'));
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);

        // Authentification avec une clé d'API
        $client->setDeveloperKey('AIzaSyCxxKnWhC3mcOalpB-FCWJoA9Kg9jSCnPs'); // Remplacez YOUR_API_KEY_HERE par votre clé d'API Google Calendar

        // Création du service Google Calendar
        $service = new Google_Service_Calendar($client);

        // Récupération des événements
        $calendarId = 'charriersim@gmail.com';
        $optParams = [
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        ];
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        // Formater les événements
        $formattedEvents = [];
        foreach ($events as $event) {
            // Récupérer l'identifiant de l'événement
            $eventId = $event->getId();

            $recuring = $event->getRecurringEventId();
            $isRecurrent = false;

            // Si l'événement est récurrent et qu'il existe déjà dans la liste des événements formatés, passer à l'événement suivant
            if ($recuring != null) {
                $isRecurrent = true;
            }

            // Ajouter l'événement à la liste des événements formatés
            $formattedEvents[$eventId] = [
                'id' => $eventId,
                'title' => $event->getSummary(),
                'description' => $event->getDescription(),
                'location' => $event->getLocation(),
                'start' => $event->getStart()->dateTime ?: $event->getStart()->date,
                'end' => $event->getEnd()->dateTime ?: $event->getEnd()->date,
                'attendees' => $event->getAttendees(),
                'isRecurring' => $isRecurrent, // Indiquer si l'événement est récurrent
            ];
        }

        // Créer des objets Event à partir des données formatées
        $eventObjects = [];
        foreach ($formattedEvents as $eventData) {
            // Appeler la méthode getCategories sur chaque objet Event
            $categories = explode(',', $eventData['description']);

            // Créer un nouvel objet Event en utilisant les données formatées et les catégories extraites
            $event = new Event(
                $eventData['id'],
                $eventData['title'],
                $eventData['start'],
                $eventData['end'],
                $categories, // Utilisez les catégories extraites ici
                $eventData['location'],
                $eventData['attendees'],
                $eventData['isRecurring']
            );
            $eventObjects[] = $event;
        }

        // Retourner les événements au format JSON
        return response()->json([
            'events' => $eventObjects, // Retournez les objets Event au lieu des données formatées
        ]);
    }

    public function getCategories(Request $request): JsonResponse
    {
        // Configuration de l'accès à l'API Google Calendar
        $client = new Google_Client();
        $client->setAuthConfig(config_path('google/credentials.json'));
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);

        // Authentification avec une clé d'API
        $client->setDeveloperKey('AIzaSyCxxKnWhC3mcOalpB-FCWJoA9Kg9jSCnPs');

        // Création du service Google Calendar
        $service = new Google_Service_Calendar($client);

        // Récupération des événements
        $calendarId = 'charriersim@gmail.com';
        $optParams = [
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        ];
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        // Créer un tableau associatif pour stocker les catégories par ID d'événement
        $categoriesById = [];

        // Parcourir chaque événement et extraire les catégories de sa description
        foreach ($events as $event) {
            // Récupérer l'identifiant de l'événement
            $eventId = $event->getId();

            // Extraire les catégories de la description de l'événement
            $categories = explode(',', $event->getDescription());

            // Stocker les catégories dans le tableau associatif en les liant à l'identifiant de l'événement
            $categoriesById[$eventId] = $categories;
        }

        // Retourner les catégories par ID d'événement au format JSON
        return response()->json([
            'categoriesById' => $categoriesById,
        ]);
    }

    public function getCategoriesById(Request $request, $eventId): JsonResponse
    {
        // Configuration de l'accès à l'API Google Calendar
        $client = new Google_Client();
        $client->setAuthConfig(config_path('google/credentials.json'));
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);

        // Authentification avec une clé d'API
        $client->setDeveloperKey('AIzaSyCxxKnWhC3mcOalpB-FCWJoA9Kg9jSCnPs');

        // Création du service Google Calendar
        $service = new Google_Service_Calendar($client);

        // Récupération de l'événement spécifié par son ID
        $event = $service->events->get('charriersim@gmail.com', $eventId);

        // Extraire les catégories de la description de l'événement
        $categories = explode(',', $event->getDescription());

        // Retourner les catégories au format JSON
        return response()->json([
            'eventId' => $eventId,
            'categories' => $categories,
        ]);
    }

    public function getEventsByCategory(Request $request, $category): JsonResponse
    {
        // Configuration de l'accès à l'API Google Calendar
        $client = new Google_Client();
        $client->setAuthConfig(config_path('google/credentials.json'));
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);

        // Authentification avec une clé d'API
        $client->setDeveloperKey('AIzaSyCxxKnWhC3mcOalpB-FCWJoA9Kg9jSCnPs');

        // Création du service Google Calendar
        $service = new Google_Service_Calendar($client);

        // Récupération des événements
        $calendarId = 'charriersim@gmail.com';
        $optParams = [
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        ];
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        // Filtrer les événements pour ceux qui contiennent la catégorie spécifiée dans leur description
        $filteredEvents = [];
        foreach ($events as $event) {
            $description = $event->getDescription();
            if ($description && strpos($description, $category) !== false) {
                $filteredEvents[] = $event;
            }
        }

        // Retourner les événements filtrés au format JSON
        return response()->json([
            'events' => $filteredEvents,
        ]);
    }


}
