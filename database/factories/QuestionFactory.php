<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'questionnaire_id' => Questionnaire::factory(),
            'text' => $this->faker->sentence(8),
            'position' => $this->faker->numberBetween(1, 100),
        ];
    }
}
