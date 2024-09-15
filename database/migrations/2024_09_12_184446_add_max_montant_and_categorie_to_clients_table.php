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
        Schema::table('clients', function (Blueprint $table) {
            $table->decimal('max_montant', 10, 2)->nullable()->after('telephone');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete()->after('telephone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('max_montant');
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
