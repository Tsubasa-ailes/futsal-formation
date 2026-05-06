<?php

namespace App\Http\Controllers;

use App\Models\Lineup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LineupController extends Controller
{
    public function index()
    {
        $lineups = Lineup::withCount('players')
            ->latest()
            ->get();

        return view('lineups.index', compact('lineups'));
    }

    public function show(Lineup $lineup)
    {
        $lineup->load(['players' => function($query) {
            $query->orderBy('slot');
        }]);

        return view('lineups.show', compact('lineup'));
    }

    public function edit(Lineup $lineup)
    {
        $lineup->load(['players' => function ($query) {
            $query->orderBy('slot');
        }]);

        return view('lineups.edit', compact('lineup'));
    }

    public function update(Request $request, Lineup $lineup)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:20'],
            'players' => ['required', 'array'],
            'players.*.id' => ['required', 'exists:lineup_players,id'],
            'players.*.display_name' => ['required', 'string', 'max:20'],
        ]);

        DB::transaction(function () use ($validated, $lineup) {
            $lineup->update([
                'title' => $validated['title'] ?? '無題',
            ]);

            foreach ($validated['players'] as $playerData) {
                $lineup->players()
                    ->where('id', $playerData['id'])
                    ->update([
                        'display_name' => $playerData['display_name'],
                    ]);
            }
        });

        return redirect()
            ->route('lineups.show', $lineup)
            ->with('success', 'フォーメーションを更新しました。');
    }

    public function destroy(Lineup $lineup)
    {

        DB::transaction(function () use ($lineup) {
            $lineup->players()->delete();

            $lineup->delete();
        });

        return redirect()
            ->route('lineups.index')
            ->with('success', 'フォーメーションを削除しました。');
    }

    public function trash()
    {
        $lineups = Lineup::onlyTrashed()
            ->withCount(['players' => function ($query) {
                $query->withTrashed();
            }]  )
            ->latest('deleted_at')
            ->get();

        return view('lineups.trash', compact('lineups'));
    }

    public function restore($id)
    {
        DB::transaction(function () use ($id) {

            $lineup = Lineup::onlyTrashed()->findOrFail($id);

            $lineup->restore();

            $lineup->players()
                ->onlyTrashed()
                ->restore();
        });

        return redirect()
            ->route('lineups.trash')
            ->with('success', 'フォーメーションを復元しました。');
    }

    public function forceDelete($id)
    {
        DB::transaction(function () use ($id) {
            $lineup = Lineup::onlyTrashed()->findOrFail($id);
        
            $lineup->players()
                ->withTrashed()
                ->forceDelete();
        
            $lineup->forceDelete();
        });
    
        return redirect()
            ->route('lineups.trash')
            ->with('success', 'フォーメーションを完全削除しました。');
}}
