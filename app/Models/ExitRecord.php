<?php

namespace App\Models;

use App\ExitStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Employee;
use App\Models\User;

class ExitRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'purpose',
        'destination',
        'exit_at',
        'return_at',
        'status',
        'recorded_by',
        'returned_by',
    ];

    protected function casts(): array
    {
        return [
            'exit_at' => 'datetime',
            'return_at' => 'datetime',
            'status' => ExitStatus::class,
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function returner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function isOut(): bool
    {
        return $this->status === ExitStatus::OUT;
    }

    public function isReturned(): bool
    {
        return $this->status === ExitStatus::RETURNED;
    }

    public function getDurationInMinutes(): ?int
    {
        if (! $this->return_at) {
            return null;
        }

        return $this->exit_at->diffInMinutes($this->return_at);
    }
}