<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade'); // Référence à un client
            $table->string('etat')->nullable(); // Champ état similaire à dettes
            $table->date('date_echeance')->nullable(); // Date d'échéance
            $table->decimal('montant_total', 10, 2)->default(0); // Montant total de la demande
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demandes');
    }
};
