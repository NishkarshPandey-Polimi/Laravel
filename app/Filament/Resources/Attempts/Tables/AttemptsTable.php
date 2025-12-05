<?php

namespace App\Filament\Resources\Attempts\Tables;

use App\Filament\Resources\Attempts\AttemptResource;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
            ])
            // Clicking the row opens the View page:
            ->recordUrl(fn($record) => AttemptResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
