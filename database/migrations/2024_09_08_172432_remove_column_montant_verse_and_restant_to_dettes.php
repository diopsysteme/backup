<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dettes', function (Blueprint $table) {
            $table->dropColumn('montant_verse');
            $table->dropColumn('montant_restant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dettes', function (Blueprint $table) {
            
            $table->decimal('montant_verse', 15, 2)->after('montant');
            $table->decimal('montant_restant', 15, 2)->after('montant_verse');
        });
    }
};
