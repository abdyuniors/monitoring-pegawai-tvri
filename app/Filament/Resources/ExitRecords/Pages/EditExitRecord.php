<?php

namespace App\Filament\Resources\ExitRecords\Pages;

use App\Filament\Resources\ExitRecords\ExitRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExitRecord extends EditRecord
{
    protected static string $resource = ExitRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
