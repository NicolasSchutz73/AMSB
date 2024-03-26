<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Team;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function show(Request $request)
    {
        $events = Event::all(); // Récupère tous les événements pour simplifier

        // Récupérer les catégories depuis un modèle Team ou autre logique
        $categories = Team::all()->pluck('name'); // Exemple de récupération des noms de catégories

        return view('calendar', [
            'events' => $events,
            'categories' => $categories,
        ]);
    }



}
