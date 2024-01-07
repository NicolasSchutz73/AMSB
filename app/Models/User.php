<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';
    public $timestamps = false;
    protected $primaryKey = 'ID_user';
    protected $fillable = [
        'nom', 'prenom', 'mail', 'Mdp',
    ];

    public function getAuthPassword()
    {
        return $this->Mdp; // Mettez à jour cette ligne
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Hasher le mot de passe ici si nécessaire
        });
    }

}
