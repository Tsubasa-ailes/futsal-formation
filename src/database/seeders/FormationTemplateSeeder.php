<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FormationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 既存データを消して入れ直したい場合
            DB::table('formation_slots')->delete();
            DB::table('formation_templates')->delete();

            // PostgreSQLでIDを1から振り直したい場合
            DB::statement("TRUNCATE TABLE formation_slots, formation_templates RESTART IDENTITY CASCADE");

            $templates = [
                [
                    'formation_code' => '2-2',
                    'name' => '2-2',
                    'slots' => [
                        ['slot' => 1, 'default_x' => 50.00, 'default_y' => 85.00, 'role_label' => 'GK'],
                        ['slot' => 2, 'default_x' => 30.00, 'default_y' => 55.00, 'role_label' => 'DF'],
                        ['slot' => 3, 'default_x' => 70.00, 'default_y' => 55.00, 'role_label' => 'DF'],
                        ['slot' => 4, 'default_x' => 35.00, 'default_y' => 25.00, 'role_label' => 'FW'],
                        ['slot' => 5, 'default_x' => 65.00, 'default_y' => 25.00, 'role_label' => 'FW'],
                    ],
                ],
                [
                    'formation_code' => '1-2-1',
                    'name' => '1-2-1',
                    'slots' => [
                        ['slot' => 1, 'default_x' => 50.00, 'default_y' => 85.00, 'role_label' => 'GK'],
                        ['slot' => 2, 'default_x' => 50.00, 'default_y' => 58.00, 'role_label' => 'DF'],
                        ['slot' => 3, 'default_x' => 30.00, 'default_y' => 38.00, 'role_label' => 'MF'],
                        ['slot' => 4, 'default_x' => 70.00, 'default_y' => 38.00, 'role_label' => 'MF'],
                        ['slot' => 5, 'default_x' => 50.00, 'default_y' => 18.00, 'role_label' => 'FW'],
                    ],
                ],
                [
                    'formation_code' => '3-1',
                    'name' => '3-1',
                    'slots' => [
                        ['slot' => 1, 'default_x' => 50.00, 'default_y' => 85.00, 'role_label' => 'GK'],
                        ['slot' => 2, 'default_x' => 25.00, 'default_y' => 52.00, 'role_label' => 'DF'],
                        ['slot' => 3, 'default_x' => 50.00, 'default_y' => 58.00, 'role_label' => 'DF'],
                        ['slot' => 4, 'default_x' => 75.00, 'default_y' => 52.00, 'role_label' => 'DF'],
                        ['slot' => 5, 'default_x' => 50.00, 'default_y' => 20.00, 'role_label' => 'FW'],
                    ],
                ],
            ];

            foreach ($templates as $template) {
                $templateId = DB::table('formation_templates')->insertGetId([
                    'formation_code' => $template['formation_code'],
                    'name' => $template['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($template['slots'] as $slot) {
                    DB::table('formation_slots')->insert([
                        'formation_template_id' => $templateId,
                        'slot' => $slot['slot'],
                        'default_x' => $slot['default_x'],
                        'default_y' => $slot['default_y'],
                        'role_label' => $slot['role_label'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }
}