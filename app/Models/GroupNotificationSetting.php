<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupNotificationSetting extends Model
{
    use HasFactory;

    // Nom de la table si différent du nom par défaut
    protected $table = 'group_notification_settings';

    // Définition des attributs qui sont mass assignable
    protected $fillable = ['group_id', 'user_id', 'notifications_enabled'];

    /**
     * Relation avec le modèle User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le modèle Group.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
