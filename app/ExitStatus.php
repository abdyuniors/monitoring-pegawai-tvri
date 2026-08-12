<?php

namespace App;

enum ExitStatus: string
{
    case OUT = 'out';
    case RETURNED = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::OUT => 'Sedang Keluar',
            self::RETURNED => 'Sudah Kembali',
        };
    }
}
