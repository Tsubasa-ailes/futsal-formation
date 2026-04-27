<?php

namespace App\Http\Controllers;

use App\Models\Lineup;
use Illuminate\Http\Request;

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
}
