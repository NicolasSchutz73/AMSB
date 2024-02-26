<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'user_id', 'content'];

    /**
     * Obtient le groupe associé au message.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function files()
    {
        return $this->hasMany(File::class);
    }



    // Vous pouvez ajouter des méthodes supplémentaires ici si nécessaire
}
