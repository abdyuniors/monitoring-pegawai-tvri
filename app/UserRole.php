<?php

namespace App;

enum UserRole: string
{
    case ADMIN = 'admin';
    case SECURITY = 'security';
    case PIMPINAN = 'pimpinan';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::SECURITY => 'Security',
            self::PIMPINAN => 'Pimpinan',
        };
    }
}
