<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use App\Models\User;
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
            'teams' => Team::orderBy('id')->paginate(8)
        ]);
    }

    /**
     * Affiche le formulaire de création d'une nouvelle équipe.
     * @return View
     */
    public function create(): View
    {
        // Récupère tous les utilisateurs disponibles
        $allUsers = User::all();

        // Instancier un objet Team (vide) pour accéder aux noms des champs
        $team = new Team;

        // Passer les champs du modèle à la vue
        return view('teams.create', compact('team', 'allUsers'));
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
        $team = Team::create([
            'name' => $validatedData['name'],
            'category' => $validatedData['category'],
            // Ajoute d'autres propriétés si nécessaire
        ]);

        // Attacher les utilisateurs à l'équipe
        if (isset($validatedData['add_users'])) {
            $team->users()->attach($validatedData['add_users']);
        }

        // Rediriger vers la page de la liste des équipes avec un message
        return redirect()->route('teams.index')->with('success', 'Équipe créée avec succès.');
    }

    /**
     * Affiche les détails de l'équipe spécifiée.
     * @param Team $team
     * @return View
     */
    public function show(Team $team): View
    {
        // Charger les utilisateurs liés à cette équipe
        $users = $team->users;

        // Charger les entraineurs liés à cette équipe
        $coaches = $team->users()->whereHas('roles', function ($query) {
            $query->where('name', 'coach');
        })->get();

        return view('teams.show', [
            'team' => $team,
            'users' => $users,
            'coaches' => $coaches,
        ]);
    }

    /**
     * Affiche le formulaire d'édition de l'équipe spécifié.
     * @param Team $team
     * @return View
     */
    public function edit(Team $team): View
    {
        $allUsers = User::all();

        return view('teams.edit', [
            'team' => $team,
            'allUsers' => $allUsers,
        ]);
    }

    /**
     * Met à jour l'équipe spécifié en base de données.
     * @param UpdateTeamRequest $request
     * @param Team $team
     * @return RedirectResponse
    */
    public function update(UpdateTeamRequest $request, Team $team): RedirectResponse
    {
        $validatedData = $request->validated();

        // Met à jour les propriétés de l'équipe
        $team->update([
            'name' => $validatedData['name'],
            'category' => $validatedData['category'],
        ]);

        // Ajoute les utilisateurs à l'équipe
        if (isset($validatedData['add_users'])) {
            $team->users()->attach($validatedData['add_users']);
        }

        // Supprime les utilisateurs de l'équipe
        if (isset($validatedData['remove_users'])) {
            $team->users()->detach($validatedData['remove_users']);
        }

        // Redirige vers la page de détails de l'équipe
        return redirect()->route('teams.show', $team->id)->with('success', 'L\'équipe a été mise à jour avec succès.');
    }

    /**
     * Supprime l'équipe spécifiée de la base de données.
     * @param Team $team
     * @return RedirectResponse
     */
    public function destroy(Team $team):RedirectResponse
    {
        // Supprime l'équipe de la base de données
        $team->delete();

        return redirect()->route('teams.index');
    }

    public function showUserTeams()
    {
        $user = auth()->user();
        $teams = $user->team()->get(); // Récupérer toutes les équipes associées à l'utilisateur

        return view('user_teams.show', ['teams' => $teams]);
    }

}
