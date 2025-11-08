<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\Option;
use App\Models\Attempt;
use App\Models\Answer;
use Illuminate\Support\Facades\DB;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Create 3 questionnaires
            $questionnaires = Questionnaire::factory(3)->create();

            foreach ($questionnaires as $qnr) {
                // Create 5 questions per questionnaire
                $questions = Question::factory(5)
                    ->for($qnr)
                    ->create();

                foreach ($questions as $question) {
                    // Create 4 options per question; ensure 1 correct
                    $options = Option::factory(4)
                        ->for($question)
                        ->create();

                    // Pick one option to be correct
                    $correct = $options->random();
                    $correct->update(['is_correct' => true]);
                }

                // Create attempts for this questionnaire
                // We'll create 3 attempts per questionnaire
                $attempts = Attempt::factory(3)
                    ->for($qnr)
                    ->create()
                    ->each(function (Attempt $attempt) use ($qnr) {
                        $questions = $qnr->questions()->with('options')->get();

                        $correctCount = 0;
                        $total = $questions->count();

                        foreach ($questions as $question) {
                            // choose an option (simulate 70% chance to pick correct)
                            $options = $question->options;
                            if ($options->isEmpty()) {
                                continue;
                            }

                            $pickCorrect = (random_int(1, 100) <= 70);

                            if ($pickCorrect) {
                                $selected = $options->where('is_correct', true)->first();
                            } else {
                                $selected = $options->where('is_correct', false)->random();
                            }

                            $isCorrect = (bool) ($selected->is_correct ?? false);

                            Answer::create([
                                'attempt_id' => $attempt->id,
                                'question_id' => $question->id,
                                'selected_option_id' => [$selected->id], // store as array in JSON
                                'is_correct' => $isCorrect,
                            ]);

                            if ($isCorrect) {
                                $correctCount++;
                            }
                        }

                        // finalize attempt scoring
                        $scorePct = $total ? (int) round(($correctCount / $total) * 100) : 0;
                        $attempt->update([
                            'submitted_at' => now(),
                            'score_cached' => $scorePct,
                            'wrong_count_cached' => max(0, $total - $correctCount),
                            'passed' => $scorePct >= $qnr->passing_score,
                        ]);
                    });
            }
        });

        $this->command->info('Questionnaires with questions, options and attempts seeded.');
    }
}
