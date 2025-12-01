<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\Attempt;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    // GET /questionnaires/{id}
    public function show(Request $request, $id)
    {
        $questionnaire = Questionnaire::with(['Question.Answer'])
            ->where('is_active', true)
            ->findOrFail($id);

        return view('questionnaire.show', compact('questionnaire'));
    }

    // POST /questionnaires/{id}
    public function submit(Request $request, $id)
    {
        $questionnaire = Questionnaire::with(['Question.Answer'])->findOrFail($id);

        // Validate name and surname along with responses
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'responses' => 'required|array'
        ]);

        $responses = $data['responses'];
        $totalQuestions = $questionnaire->Question->count();
        $correct = 0;

        foreach ($questionnaire->Question as $question) {
            $answerId = $responses[$question->id] ?? null;
            if ($answerId) {
                $isCorrect = $question->Answer->where('id', $answerId)->pluck('is_correct')->first();
                if ($isCorrect) {
                    $correct++;
                }
            }
        }

        $scorePercent = $totalQuestions > 0 ? ($correct / $totalQuestions) * 100 : 0;
        $passingScore = (float)$questionnaire->passing_score;
        $result = $scorePercent >= $passingScore ? 'Passed' : 'Failed';
        $message = $result === 'Passed'
            ? "Congrats you have passed"
            : "Sorry you didn't score enough, try again.";

        // Save the attempt
        Attempt::create([
            'questionnaire_id' => $questionnaire->id,
            'name' => $data['name'],
            'surname' => $data['surname'],
            'score' => $scorePercent,
            'result' => $result,
        ]);

        return redirect()->back()->with('status', $message);
    }

    // GET /
    public function index()
    {
        $questionnaires = Questionnaire::where('is_active', true)->get();
        return view('questionnaire.index', compact('questionnaires'));
    }
}
