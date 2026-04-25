<?php

namespace App\Http\Controllers;

use App\Models\FormationTemplate;
use App\Models\Lineup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayController extends Controller
{
    public function index(Request $request)
    {
        $templates = FormationTemplate::orderBy('id')->get();

        $selectedTemplateId = $request->get('formation_template_id');

        if ($selectedTemplateId) {
            $selectedTemplate = FormationTemplate::with('slots')->findOrFail($selectedTemplateId);
        } else {
            $selectedTemplate = FormationTemplate::with('slots')->orderBy('id')->first();
        }

        return view('play.index', compact('templates', 'selectedTemplate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'formation_template_id' => ['required', 'exists:formation_templates,id'],
            'title' => ['nullable', 'string', 'max:20'],
            'players' => ['required', 'array'],
            'players.*.slot' => ['required', 'integer'],
            'players.*.display_name' => ['required', 'string', 'max:20'],
            'players.*.x' => ['required', 'numeric'],
            'players.*.y' => ['required', 'numeric'],
        ]);
        
        DB::transaction(function () use ($validated) {
            $template = FormationTemplate::findOrFail($validated['formation_template_id']);

            $lineup = Lineup::create([
                'title' => $validated['title'] ?? '無題のフォーメーション',
                'formation_code' => $template->formation_code,
                'note' => null,
            ]);

            foreach ($validated['players'] as $player) {
                $lineup->players()->create([
                    'slot' => $player['slot'],
                    'display_name' => $player['display_name'],
                    'number' => null,
                    'x' => $player['x'],
                    'y' => $player['y'],
                ]);
            }
        });

        return redirect()
            ->route('play.index', ['formation_template_id' => $validated['formation_template_id']])
            ->with('success', 'フォーメーションを保存しました。');
    }
}