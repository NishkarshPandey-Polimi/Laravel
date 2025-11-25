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
        $questionnaire = Questionnaire::with(['Question.Answer'])->findOrFail($id);
        $responses = $request->input('responses', []);
        $totalQuestions = $questionnaire->Question->count();
        $correct = 0;

        foreach ($questionnaire->Question as $question) {
            $answerId = $responses[$question->id] ?? null;
            if ($answerId) {
                // Find if selected answer is correct
                $isCorrect = $question->Answer->where('id', $answerId)->pluck('is_correct')->first();
                if ($isCorrect) {
                    $correct++;
                }
            }
        }

        // Calculate percentage score
        $scorePercent = $totalQuestions > 0 ? ($correct / $totalQuestions) * 100 : 0;

        // Check pass/fail against required percentage (cast to float for safety)
        $passingScore = (float) $questionnaire->passing_score;
        $message = $scorePercent >= $passingScore
            ? "Congrats you have passed"
            : "Sorry you didn't score enough, try again.";

        return redirect()->back()
            ->with('status', $message);
    }

    public function index()
    {
        // Get all active questionnaires
        $questionnaires = Questionnaire::where('is_active', true)->get();

        return view('questionnaire.index', compact('questionnaires'));
    }
}
