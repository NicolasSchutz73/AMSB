<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->string('id')->primary(); // Utilisation de l'ID de Google Calendar comme clé primaire
            $table->string('title');
            $table->text('description')->nullable(); // La description peut être null
            $table->string('location')->nullable(); // La location peut être null
            $table->dateTime('start'); // Assurez-vous que le format correspond à celui que vous utilisez
            $table->dateTime('end'); // Assurez-vous que le format correspond à celui que vous utilisez
            $table->boolean('isRecurring')->default(false); // Boolean pour les événements récurrents
            $table->timestamps(); // Crée les champs created_at et updated_at automatiquement
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
