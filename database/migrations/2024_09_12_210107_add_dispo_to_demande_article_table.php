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
        Schema::table('article_demande', function (Blueprint $table) {
            $table->integer('dispo')->default(1)->after('montant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_demande', function (Blueprint $table) {
            $table->dropColumn('dispo');
        });
    }
};
