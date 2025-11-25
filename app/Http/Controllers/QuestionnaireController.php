<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use Illuminate\Http\Request;


class QuestionnaireController extends Controller
{
    // GET /questionnaires/{id}
    public function show(Request $request, $id)
    {
        // Fetch questionnaire with its related questions and each question's answers
        $questionnaire = Questionnaire::with([
            'Question.Answer'
        ])->where('is_active', true)->findOrFail($id);

        return view('questionnaire.show', compact('questionnaire'));
    }

    // POST /questionnaires/{id}
    public function submit(Request $request, $id)
    {
        // Get submitted responses
        $responses = $request->input('responses', []);

        // You can extend: validate, store in database etc.
        // For demonstration: just show a feedback message
        return redirect()->back()->with('status', 'Submission received!')->with('responses', $responses);
    }

    public function index()
    {
        // Get all active questionnaires
        $questionnaires = Questionnaire::where('is_active', true)->get();

        return view('questionnaire.index', compact('questionnaires'));
    }
}
