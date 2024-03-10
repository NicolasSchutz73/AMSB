<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Team;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function show(Request $request)
    {
        // Récupérer la catégorie sélectionnée depuis la requête
        $category = $request->input('category');

        // Si aucune catégorie n'est sélectionnée, afficher tous les événements
        if (!$category) {
            $events = Event::getEvents($request); // Ajouter get() pour récupérer les résultats
        } else {
            // Si une catégorie est sélectionnée, filtrer les événements correspondants à cette catégorie
            $events = Event::getEventsByCategory($request, $category); // Utiliser getEventsByCategory à la place de whereCategoryIn
        }

        // Récupérer les catégories depuis EventsController
        $ekip = new Team();
        $categories = $ekip->getAllCategories();

        // Passer les événements filtrés et les catégories à la vue
        return view('calendar', [
            'events' => $events,
            'selectedCategory' => $category,
            'categories' => $categories,
        ]);
    }
}
