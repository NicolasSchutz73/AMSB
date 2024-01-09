<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Constructeur de la classe RoleController.
     * Applique les middlewares d'authentification et de permissions.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-role|edit-role|delete-role', ['only' => ['index','show']]);
        $this->middleware('permission:create-role', ['only' => ['create','store']]);
        $this->middleware('permission:edit-role', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-role', ['only' => ['destroy']]);
    }

    /**
     * Affiche la liste des rôles.
     * @return View
     */
    public function index(): View
    {
        return view('roles.index', [
            'roles' => Role::orderBy('id','DESC')->paginate(3)
        ]);
    }

    /**
     * Affiche le formulaire de création d'un nouveau rôle.
     * @return View
     */
    public function create(): View
    {
        return view('roles.create', [
            'permissions' => Permission::get()
        ]);
    }

    /**
     * Stocke un nouveau rôle dans la base de données.
     * @param StoreUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->name]);

        $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();

        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
            ->withSuccess('New role is added successfully.');
    }

    /**
     * Affiche les détails d'un rôle.
     * @param Role $role
     * @return View
     */
    public function show(Role $role): View
    {
        $rolePermissions = Permission::join("role_has_permissions","permission_id","=","id")
            ->where("role_id",$role->id)
            ->select('name')
            ->get();
        return view('roles.show', [
            'role' => $role,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'un rôle spécifié.
     * @param Role $role
     * @return View
     */
    public function edit(Role $role): View
    {
        if($role->name=='Super Admin'){
            abort(403, 'SUPER ADMIN ROLE CAN NOT BE EDITED');
        }

        $rolePermissions = DB::table("role_has_permissions")->where("role_id",$role->id)
            ->pluck('permission_id')
            ->all();

        return view('roles.edit', [
            'role' => $role,
            'permissions' => Permission::get(),
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Met à jour le rôle spécifié en base de données.
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $input = $request->only('name');

        $role->update($input);

        $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();

        $role->syncPermissions($permissions);

        return redirect()->back()
            ->withSuccess('Role is updated successfully.');
    }

    /**
     * Supprime le rôle spécifié de la base de données.
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        if($role->name=='Super Admin'){
            abort(403, 'SUPER ADMIN ROLE CAN NOT BE DELETED');
        }
        if(auth()->user()->hasRole($role->name)){
            abort(403, 'CAN NOT DELETE SELF ASSIGNED ROLE');
        }
        $role->delete();
        return redirect()->route('roles.index')
            ->withSuccess('Role is deleted successfully.');
    }
}
