<?php

namespace App\Filament\Resources\DistanceResource\Pages;

use App\Filament\Resources\DistanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDistance extends EditRecord
{
    protected static string $resource = DistanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
