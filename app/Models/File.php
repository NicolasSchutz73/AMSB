<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'message_id',
        'file_path',
        'file_type',
        'file_size',
    ];

    /**
     * Indique si les ID sont auto-increment.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * La relation avec le modèle Message.
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
