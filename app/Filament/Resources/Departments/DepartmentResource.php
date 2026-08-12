<?php

namespace App\Filament\Resources\Departments;

use App\Filament\Resources\Departments\Pages\CreateDepartment;
use App\Filament\Resources\Departments\Pages\EditDepartment;
use App\Filament\Resources\Departments\Pages\ListDepartments;
use App\Filament\Resources\Departments\Schemas\DepartmentForm;
use App\Filament\Resources\Departments\Tables\DepartmentsTable;
use App\Models\Department;
use App\UserRole;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Departemen';

    public static function canViewAny(): bool
    {
        return in_array(
            auth()->user()?->role,
            [
                UserRole::ADMIN,
                UserRole::PIMPINAN,
            ],
            true
        );
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->role === UserRole::ADMIN;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role === UserRole::ADMIN;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->role === UserRole::ADMIN;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === UserRole::ADMIN;
    }

    public static function form(Schema $schema): Schema
    {
        return DepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDepartments::route('/'),
            'create' => CreateDepartment::route('/create'),
            'edit' => EditDepartment::route('/{record}/edit'),
        ];
    }
}
