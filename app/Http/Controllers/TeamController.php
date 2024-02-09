<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
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
        // Passer les équipes paginées à la vue
        return view('teams.index', [
            'teams' => Team::latest('id')->paginate(8)
        ]);
    }

    /**
     * Affiche le formulaire de création d'une nouvelle équipe.
     * @return View
     */
    public function create(): View
    {
        // Instancier un objet Team (vide) pour accéder aux noms des champs
        $team = new Team;

        // Passer les champs du modèle à la vue
        return view('teams.create', compact('team'));
    }

    /**
     * Stocke une nouvelle équipe dans la base de données.
     * @param StoreTeamRequest $request
     * @return RedirectResponse
     */
    public function store(StoreTeamRequest $request): RedirectResponse
    {
        // Valider les données du formulaire à l'aide du StoreTeamRequest
        $validatedData = $request->validated();

        // Créer une nouvelle équipe avec les données validées
        $team = Team::create($validatedData);

        // Rediriger vers la page de la liste des équipes avec un message
        return redirect()->route('teams.index')->with('success', 'Équipe créée avec succès.');
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
