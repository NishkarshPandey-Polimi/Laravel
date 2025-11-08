<?php

namespace Database\Factories;

use App\Models\Attempt;
use App\Models\Questionnaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttemptFactory extends Factory
{
    protected $model = Attempt::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'questionnaire_id' => Questionnaire::factory(),
            'started_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'submitted_at' => null,
            'score_cached' => null,
            'wrong_count_cached' => null,
            'passed' => null,
        ];
    }
}
