<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use Illuminate\View\View;

class TeamController extends Controller
{

    /**
     * Constructeur de la classe UserController.
     * Applique les middlewares d'authentification et de permissions.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-team|edit-team|delete-team', ['only' => ['index','show']]);
        $this->middleware('permission:create-team', ['only' => ['create','store']]);
        $this->middleware('permission:edit-team', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-team', ['only' => ['destroy']]);
    }

    /**
     * Affiche la liste des équipes.
     * @return View
     */
    public function index(): View
    {
        // Récupérer toutes les équipes depuis la base de données en les triant par ID de manière décroissante et en les paginant
        $teams = Team::latest('id')->paginate(8);

        // Passer les équipes paginées à la vue
        return view('teams.index', [
            'teams' => $teams
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeamRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        //
    }
}
