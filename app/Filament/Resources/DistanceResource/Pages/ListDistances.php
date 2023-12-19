<?php

namespace App\Filament\Resources\DistanceResource\Pages;

use App\Filament\Resources\DistanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDistances extends ListRecords
{
    protected static string $resource = DistanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
