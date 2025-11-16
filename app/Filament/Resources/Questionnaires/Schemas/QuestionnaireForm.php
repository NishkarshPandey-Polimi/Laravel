<?php

namespace App\Filament\Resources\Questionnaires\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;


class QuestionnaireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title'),
                TextInput::make('passing_score')
                    ->numeric(),
                TextInput::make('description'),
                Toggle::make('is_active'),

                Repeater::make('question')
                    ->relationship('question')
                    ->schema([
                        TextInput::make('text')->required(),
                        Repeater::make('answer')
                            ->relationship('answer')
                            ->schema([
                                TextInput::make('text')->required(),
                                Toggle::make('is_correct')->default(false),
                            ])
                    ])
            ]);
    }
}
