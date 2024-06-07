<?php

namespace App\Filament\Resources\BackupLogResource\Pages;

use App\Filament\Resources\BackupLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Closure;

class ListBackupLogs extends ListRecords
{
    protected static string $resource = BackupLogResource::class;

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }
}
