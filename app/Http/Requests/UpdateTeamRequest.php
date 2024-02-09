<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'add_users' => 'array', // Valide que 'add_users' est un tableau
            'add_users.*' => 'exists:users,id', // Valide que chaque élément de 'add_users' existe dans la table 'users'
            'remove_users' => 'array', // Valide que 'remove_users' est un tableau
            'remove_users.*' => 'exists:users,id', // Valide que chaque élément de 'remove_users' existe dans la table 'users'
        ];
    }
}
