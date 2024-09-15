<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleDetteTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_dette', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade'); // Relation avec l'article
            $table->foreignId('dette_id')->constrained('dettes')->onDelete('cascade'); // Relation avec la dette
            $table->integer('qte_vente'); // Quantité vendue
            $table->decimal('prix_vente', 15, 2); // Prix de vente de l'article
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_dette');
    }
}

