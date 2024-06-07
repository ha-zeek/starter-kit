<?php

namespace App\Filament\Resources\UpdateLogResource\Pages;

use App\Filament\Resources\UpdateLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUpdateLog extends EditRecord
{
    protected static string $resource = UpdateLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
