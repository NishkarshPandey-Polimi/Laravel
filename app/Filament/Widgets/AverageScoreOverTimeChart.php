<?php

namespace App\Filament\Widgets;

use App\Models\Attempt;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AverageScoreOverTimeChart extends ChartWidget
{
    use HasFiltersSchema;

    protected ?string $heading = 'Average Score Over Time';

    protected static ?int $sort = 3;

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('preset')
                ->label('Quick range')
                ->options([
                    'all' => 'All time',
                    'last_7' => 'Last 7 days',
                    'last_30' => 'Last 30 days',
                    'this_month' => 'This month',
                    'custom' => 'Custom (use dates below)',
                ])
                ->default('last_30'),

            DatePicker::make('startDate')
                ->label('Start date'),

            DatePicker::make('endDate')
                ->label('End date'),
        ]);
    }

    protected function getData(): array
    {
        [$start, $end] = $this->getDateRange();

        $trend = Trend::model(Attempt::class)
            ->between(start: $start, end: $end)
            ->perDay()
            ->average('score');

        $labels = $trend->map(fn(TrendValue $value) => $value->date);

        return [
            'datasets' => [
                [
                    'label' => 'Average score (%)',
                    'data' => $trend->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(234,179,8)',          // yellow
                    'backgroundColor' => 'rgba(234,179,8,0.3)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getDateRange(): array
    {
        $now = CarbonImmutable::now();

        $preset = $this->filters['preset'] ?? 'last_30';
        $start = $this->filters['startDate'] ?? null;
        $end = $this->filters['endDate'] ?? null;

        return match ($preset) {
            'last_7' => [
                $now->subDays(7)->startOfDay(),
                $now->endOfDay(),
            ],
            'last_30' => [
                $now->subDays(30)->startOfDay(),
                $now->endOfDay(),
            ],
            'this_month' => [
                $now->startOfMonth(),
                $now->endOfMonth(),
            ],
            'all' => [
                $now->subYears(10)->startOfDay(),
                $now->endOfDay(),
            ],
            'custom' => [
                $start ? CarbonImmutable::parse($start)->startOfDay() : $now->subYears(10)->startOfDay(),
                $end ? CarbonImmutable::parse($end)->endOfDay() : $now->endOfDay(),
            ],
            default => [
                $now->subDays(30)->startOfDay(),
                $now->endOfDay(),
            ],
        };
    }
}
