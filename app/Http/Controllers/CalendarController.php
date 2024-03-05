<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function show()
    {
        // Récupérer toutes les catégories
        $categories = Team::getAllCategories();

        // Retourner la vue du calendrier avec les catégories
        return view('calendar', ['categories' => $categories]);
    }
}
