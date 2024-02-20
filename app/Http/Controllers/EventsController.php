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
        $client->setDeveloperKey('AIzaSyCxxKnWhC3mcOalpB-FCWJoA9Kg9jSCnPs');

        // Création du service Google Calendar
        $service = new Google_Service_Calendar($client);

        // Récupération des événements
        $calendarId = 'charriersim@gmail.com';
        $optParams = [
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        ];
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        // Formater les événements
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'id' => $event->getId(),
                'title' => $event->getSummary(),
                'description' => $event->getDescription(),
                'location' => $event->getLocation(),
                'start' => $event->getStart()->dateTime ?: $event->getStart()->date,
                'end' => $event->getEnd()->dateTime ?: $event->getEnd()->date,
                'attendees' => $event->getAttendees(),
            ];
        }




        // Créer des objets Event à partir des données formatées
        $eventObjects = [];
        foreach ($formattedEvents as $eventData) {
            $event = new Event(
                $eventData['id'],
                $eventData['title'],
                $eventData['start'],
                $eventData['end'],
                $eventData['description'], // Ajout de la description
                $eventData['location'], // Ajout du lieu
                $eventData['attendees'] // Ajout des participants
            );
            $eventObjects[] = $event;
        }

        // Retourner les événements au format JSON
        return response()->json([
            'events' => $eventObjects, // Retournez les objets Event au lieu des données formatées
        ]);
    }
}
