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
        Schema::create('lineup_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lineup_id')
                ->constrained('lineups')
                ->cascadeOnDelete();
            $table->smallInteger('slot');
            $table->string('display_name', 20);
            $table->string('number', 3)->nullable();
            $table->decimal('x', 5, 2)->nullable();
            $table->decimal('y', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['lineup_id', 'slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lineup_players');
    }
};
