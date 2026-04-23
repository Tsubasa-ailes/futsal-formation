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
        Schema::create('lineup_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lineup_id')
                ->constrained('lineups')
                ->cascadeOnDelete();
            $table->string('export_type', 20);
            $table->string('file_path', 255);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lineup_exports');
    }
};
