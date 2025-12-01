<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat as Card;
use App\Models\Questionnaire;
use App\Models\Attempt;

class QuestionnaireKpiStats extends BaseWidget
{
    protected function getCards(): array
    {
        $totalQuestionnaires = Questionnaire::count();
        $totalAttempts = Attempt::count();
        $averageScore = Attempt::whereNotNull('score')->avg('score') ?? 0;
        $globalPassRate = Attempt::where('result', 'Passed')->count() / max(1, $totalAttempts) * 100;
        $failedAttempts = Attempt::where('result', 'Failed')->count();

        return [
            Card::make('Total Questionnaires', $totalQuestionnaires)
                ->description('Total questionnaires created')
                ->color('secondary'),
            Card::make('Total Attempts', $totalAttempts)
                ->description('Total questionnaire submissions')
                ->color('primary'),
            Card::make('Average Score (%)', number_format($averageScore, 1))
                ->description('Average score across all attempts')
                ->color($averageScore >= 70 ? 'success' : 'danger'),
            Card::make('Global Pass Rate (%)', number_format($globalPassRate, 1))
                ->description('Percentage of attempts that passed')
                ->color($globalPassRate >= 70 ? 'success' : 'danger'),
            Card::make('Failed Attempts', $failedAttempts)
                ->description('Total failed attempts')
                ->color('danger'),
        ];
    }
}
