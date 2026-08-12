<?php

namespace App\Filament\Resources\ExitRecords;

use App\Filament\Resources\ExitRecords\Pages\ListExitRecords;
use App\Filament\Resources\ExitRecords\Tables\ExitRecordsTable;
use App\Filament\Resources\ExitRecords\Pages\ViewExitRecord;
use App\Filament\Resources\ExitRecords\Schemas\ExitRecordInfolist;
use App\Models\ExitRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExitRecordResource extends Resource
{
    protected static ?string $model = ExitRecord::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedArrowRightOnRectangle;

    protected static ?string $navigationLabel = 'Riwayat Keluar';

    protected static ?string $modelLabel = 'Riwayat Keluar';

    protected static ?string $pluralModelLabel = 'Riwayat Keluar';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return ExitRecordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExitRecords::route('/'),
            'view' => ViewExitRecord::route('/{record}'),
        ];
    }
    
}