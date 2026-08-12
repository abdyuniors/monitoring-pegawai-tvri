<?php

namespace App\Filament\Resources\Users\Schemas;

use App\UserRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->minLength(8)
                    ->dehydrated(fn (?string $state): bool => filled($state)),

                Select::make('role')
                    ->label('Role')
                    ->options(
                        collect(UserRole::cases())
                            ->mapWithKeys(fn (UserRole $role): array => [
                                $role->value => $role->label(),
                            ])
                            ->all()
                    )
                    ->required()
                    ->native(false),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }
}