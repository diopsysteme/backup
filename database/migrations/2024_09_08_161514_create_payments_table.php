<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dette_id')->constrained('dettes')->onDelete('cascade'); // Clé étrangère vers la dette
            $table->decimal('montant', 15, 2); // Montant du paiement
            $table->timestamp('date_paiement'); // Date du paiement
            $table->string('moyen_paiement')->nullable(); // Moyen de paiement (optionnel)
            $table->foreignId('user_id')->constrained('users'); // Utilisateur qui a enregistré le paiement
            $table->timestamps(); // Pour created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
