<?php

namespace App\Filament\Resources\Attempts\Pages;

use App\Filament\Resources\Attempts\AttemptResource;
use App\Models\Attempt;
use Filament\Resources\Pages\ViewRecord;

class ViewAttempt extends ViewRecord
{
    protected static string $resource = AttemptResource::class;

    protected static ?string $title = 'View Attempt';

    // Use a non-static property for the Blade view:
    protected string $view = 'filament.attempts.view-attempt';

    public Attempt $attempt;

    public function mount($record): void
    {
        parent::mount($record);

        $this->attempt = Attempt::with(['questionnaire', 'answers.question', 'answers.answer'])
            ->findOrFail($record);
    }

    protected function getHeaderActions(): array
    {
        // View-only
        return [];
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
