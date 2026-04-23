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
        Schema::create('formation_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_template_id')
                ->constrained('formation_templates')
                ->cascadeOnDelete();
            $table->smallInteger('slot');
            $table->decimal('default_x', 5, 2);
            $table->decimal('default_y', 5, 2);
            $table->string('role_label', 10)->nullable();
            $table->timestamps();

            $table->unique(['formation_template_id', 'slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation_slots');
    }
};
