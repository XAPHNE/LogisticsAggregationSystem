<?php

namespace App\Filament\Widgets;

use App\Models\Fleet;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class FleetsChart extends ChartWidget
{
    protected static ?string $heading = 'Fleets Chart';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = $this->getFleetsPerMonth();
        return [
            'datasets' => [
                [
                    'label' => 'Fleet Added',
                    'data' => array_values($data['fleetsPerMonth'])
                ]
            ],
            'labels' => $data['months']
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getFleetsPerMonth(): array
    {
        $now = Carbon::now();

        $fleetsPerMonth = [];
        $months = collect(range(1,12))->map(function ($month) use ($now, &$fleetsPerMonth) {
            $count = Fleet::whereMonth('created_at', Carbon::parse($now->month($month)->format('Y-m')))->count();
            $fleetsPerMonth[] = $count;

            return $now->month($month)->format('M');
        })->toArray();

        return [
            'fleetsPerMonth' => $fleetsPerMonth,
            'months' => $months
        ];
    }
}
