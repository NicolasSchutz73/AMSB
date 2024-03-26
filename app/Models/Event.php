<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Http\Controllers\EventsController;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    protected $table = 'events'; // Défini seulement si le nom de la table n'est pas la version plurielle du nom du modèle

    protected $fillable = [
        'id', 'title', 'description', 'location', 'start', 'end', 'isRecurring'
    ];

    protected $casts = [
        'isRecurring' => 'boolean',
        // Si 'attendees' doit être stocké en tant que JSON, ajoutez-le ici et assurez-vous que votre DB le supporte
        // 'attendees' => 'array',
    ];

    public $incrementing = false;
    protected $keyType = 'string';


}
