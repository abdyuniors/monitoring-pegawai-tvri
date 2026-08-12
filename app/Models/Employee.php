<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ExitRecord;
use App\Models\Department;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'name',
        'department_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function exitRecords(): HasMany
    {
        return $this->hasMany(ExitRecord::class);
    }
}