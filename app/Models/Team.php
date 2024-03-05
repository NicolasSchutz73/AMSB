<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category'
    ];

    // Relation Many-to-Many avec les utilisateurs (joueurs et parents)
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id')->withTimestamps();
    }

    // Relation Many-to-Many avec les coachs
    public function coach()
    {
        // Utilise la relation Many-to-Many 'users()' pour récupérer tous les utilisateurs liés à l'équipe
        return $this->users()->whereHas('roles', function ($query) {
            // Filtre les utilisateurs ayant le rôle "coach"
            $query->where('name', 'coach');
        });
    }

    public static function getAllCategories()
    {
        // Récupérer uniquement les noms des équipes (colonne 'name')
        return self::pluck('name')->toArray();
    }

}
