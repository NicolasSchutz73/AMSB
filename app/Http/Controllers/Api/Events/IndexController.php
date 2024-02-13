<?php

namespace App\Http\Controllers\Api\Events;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Google\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Configuration de l'accès à l'API Google Calendar
        $client = new Google_Client();
        $client->setDeveloperKey('AIzaSyCxxKnWhC3mcOalpB-FCWJoA9Kg9jSCnPs'); // Remplacez 'VOTRE_CLE_API' par votre clé d'API

        // Création du service Google Calendar
        $service = new Google_Service_Calendar($client);

        // Récupération des événements
        $calendarId = 'charriersim@gmail.com'; // Remplacez par l'ID de votre calendrier Google
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
                'start' => $event->start->dateTime ?: $event->start->date,
                'end' => $event->end->dateTime ?: $event->end->date,
            ];
        }

        // Retourner les événements au format JSON
        return response()->json([
            'events' => $formattedEvents,
        ]);
    }
}
