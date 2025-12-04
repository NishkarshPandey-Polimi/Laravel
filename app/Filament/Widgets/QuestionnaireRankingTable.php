<?php

namespace App\Filament\Widgets;

use App\Models\Questionnaire;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class QuestionnaireRankingTable extends BaseWidget
{
    protected static ?string $heading = 'Questionnaire Ranking';

    protected static ?int $sort = 4;

    protected function getTableQuery(): Builder
    {
        return Questionnaire::query()
            ->withCount('Question') // optional, if you want
            ->withCount('Attempt')  // make sure Questionnaire has attempts() relation
            ->withAvg('Attempt as attempts_avg_score', 'score')
            ->withCount(['Attempt as attempts_passed_count' => function ($q) {
                $q->where('result', 'Passed');
            }])
            ->orderByDesc('attempt_count'); // most attempts first
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->label('Questionnaire')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('attempt_count')
                ->label('Total attempts')
                ->sortable(),

            Tables\Columns\TextColumn::make('attempts_avg_score')
                ->label('Average score')
                ->formatStateUsing(fn($state) => $state ? number_format($state, 1) . '%' : '-')
                ->sortable(),

            Tables\Columns\TextColumn::make('pass_rate')
                ->label('Pass rate')
                ->getStateUsing(function ($record) {
                    $total = $record->attempt_count ?: 0;
                    $passed = $record->attempts_passed_count ?? 0;
                    if ($total === 0) {
                        return '-';
                    }
                    $rate = ($passed / $total) * 100;
                    return number_format($rate, 1) . '%';
                }),
        ];
    }
}
