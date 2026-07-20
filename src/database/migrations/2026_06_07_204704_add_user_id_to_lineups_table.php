<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lineups', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        $userId = DB::table('users')->value('id');

        if (!$userId) {
            throw new RuntimeException('lineups.user_id を設定するための users レコードが存在しません。');
        }

        DB::table('lineups')
            ->whereNull('user_id')
            ->update(['user_id' => $userId]);

        DB::statement('ALTER TABLE lineups ALTER COLUMN user_id SET NOT NULL');
    }

    public function down(): void
    {
        Schema::table('lineups', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};