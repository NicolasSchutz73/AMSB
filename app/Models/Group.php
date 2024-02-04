<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Récupère les messages associés au groupe.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }



}

