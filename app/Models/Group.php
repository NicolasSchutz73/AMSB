<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    public function users()
    {
        // Assurez-vous d'inclure le champ last_read_at dans la relation
        return $this->belongsToMany(User::class, 'group_user')
            ->withPivot('last_visited_at');
    }


    /**
     * Récupère les messages associés au groupe.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }




    protected $fillable = ['name', 'type'];

}

