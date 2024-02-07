<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'description',
        'emergency',
        'password',
        'profile_photo_path',
        'document_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany((Course::class));
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * Vérifie si l'utilisateur est membre d'un groupe spécifique.
     *
     * @param int $groupId L'ID du groupe à vérifier.
     * @return bool Retourne true si l'utilisateur est membre du groupe, sinon false.
     */
    public function isMemberOfGroup( $groupId): bool
    {
        return $this->groups()->where('groups.id', $groupId)->exists();
    }
}
