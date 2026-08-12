<?php

namespace App\Filament\Resources\ExitRecords\Pages;

use App\Filament\Resources\ExitRecords\ExitRecordResource;
use Filament\Resources\Pages\ViewRecord;

class ViewExitRecord extends ViewRecord
{
    protected static string $resource = ExitRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}