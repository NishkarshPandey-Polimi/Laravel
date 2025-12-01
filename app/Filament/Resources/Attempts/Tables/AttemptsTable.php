<?php

namespace App\Filament\Resources\Attempts\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AttemptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('questionnaire.title')
                    ->label('Questionnaire')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('surname')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('score')
                    ->label('Score (%)')
                    ->formatStateUsing(fn($state) => number_format($state, 1) . '%')
                    ->sortable(),

                TextColumn::make('result')
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => $state === 'Passed' ? 'success' : 'danger'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
