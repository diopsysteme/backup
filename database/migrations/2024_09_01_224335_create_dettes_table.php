    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateDettesTable extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('dettes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained()->onDelete('cascade'); // Relation avec le client
                $table->date('date');
                $table->decimal('montant', 15, 2);  // Montant total de la dette
                $table->decimal('montant_verse', 15, 2); // Montant dû
                $table->decimal('montant_restant', 15, 2);
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('dettes');
        }
    }

