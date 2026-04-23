<?php

namespace App\Http\Controllers;

use App\Models\FormationTemplate;
use Illuminate\Http\Request;

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
}