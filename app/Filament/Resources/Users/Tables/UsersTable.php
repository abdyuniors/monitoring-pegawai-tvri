<?php

namespace App\Filament\Resources\Users\Tables;

use App\UserRole;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('username')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('role')
                    ->label('Role')
                    ->formatStateUsing(
                        fn (UserRole|string|null $state): string =>
                            $state instanceof UserRole
                                ? $state->label()
                                : ($state
                                    ? ucfirst($state)
                                    : '-')
                    ),

                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(
                        fn (bool $state): string =>
                            $state ? 'Aktif' : 'Nonaktif'
                    )
                    ->colors([
                        'success' => fn (bool $state): bool => $state,
                        'danger' => fn (bool $state): bool => ! $state,
                    ]),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Role')
                    ->options(
                        collect(UserRole::cases())
                            ->mapWithKeys(
                                fn (UserRole $role): array => [
                                    $role->value => $role->label(),
                                ]
                            )
                            ->all()
                    ),

                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif')
                    ->placeholder('Semua'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}