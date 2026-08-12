<?php

namespace App\Filament\Pages;

use App\ExitStatus;
use App\UserRole;
use App\Models\Department;
use App\Models\ExitRecord;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;

class EmployeeExitReport extends Page
{
    protected string $view = 'filament.pages.employee-exit-report';

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedDocumentChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Monitoring';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $title = 'Laporan Aktivitas Pegawai';

    protected static ?string $slug = 'laporan-aktivitas-pegawai';

    public string $startDate = '';

    public string $endDate = '';

    public string $departmentId = '';

    public static function canAccess(): bool
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

    public function mount(): void
    {
        $this->startDate = now()
            ->startOfMonth()
            ->format('Y-m-d');

        $this->endDate = now()->format('Y-m-d');
    }

    /**
     * Daftar departemen aktif untuk filter.
     */
    public function getDepartmentsProperty()
    {
        return Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Data laporan berdasarkan filter.
     */
    public function getRecordsProperty()
    {
        if (
            $this->startDate &&
            $this->endDate &&
            $this->startDate > $this->endDate
        ) {
            return collect();
        }

        return ExitRecord::query()
            ->with([
                'employee.department',
                'recorder',
                'returner',
            ])

            // Filter tanggal mulai
            ->when(
                $this->startDate,
                fn (Builder $query) =>
                    $query->whereDate(
                        'exit_at',
                        '>=',
                        $this->startDate
                    )
            )

            // Filter tanggal selesai
            ->when(
                $this->endDate,
                fn (Builder $query) =>
                    $query->whereDate(
                        'exit_at',
                        '<=',
                        $this->endDate
                    )
            )

            // Filter departemen
            ->when(
                $this->departmentId,
                fn (Builder $query) =>
                    $query->whereHas(
                        'employee',
                        fn (Builder $employeeQuery) =>
                            $employeeQuery->where(
                                'department_id',
                                $this->departmentId
                            )
                    )
            )

            ->latest('exit_at')
            ->get();
    }

    /**
     * Total transaksi.
     */
    public function getTotalProperty(): int
    {
        return $this->records->count();
    }

    /**
     * Total pegawai yang sudah kembali.
     */
    public function getReturnedProperty(): int
    {
        return $this->records
            ->where('status', ExitStatus::RETURNED)
            ->count();
    }

    /**
     * Total pegawai yang masih berada di luar.
     */
    public function getStillOutProperty(): int
    {
        return $this->records
            ->where('status', ExitStatus::OUT)
            ->count();
    }

    /**
     * Rata-rata durasi pegawai berada di luar.
     */
    public function getAverageDurationProperty(): string
    {
        $durations = $this->records
            ->map(
                fn (ExitRecord $record) =>
                    $record->getDurationInMinutes()
            )
            ->filter(
                fn (?int $duration) =>
                    $duration !== null
            );

        if ($durations->isEmpty()) {
            return '—';
        }

        $average = round($durations->average());

        if ($average < 60) {
            return "{$average} menit";
        }

        $hours = intdiv($average, 60);
        $minutes = $average % 60;

        if ($minutes === 0) {
            return "{$hours} jam";
        }

        return "{$hours} jam {$minutes} menit";
    }

    /**
     * Nama departemen yang sedang dipilih.
     */
    public function getSelectedDepartmentNameProperty(): string
    {
        if (! $this->departmentId) {
            return 'Semua Departemen';
        }

        return $this->departments
            ->firstWhere('id', (int) $this->departmentId)
            ?->name
            ?? 'Semua Departemen';
    }

    /**
     * Label periode laporan.
     */
    public function getPeriodLabelProperty(): string
    {
        if (! $this->startDate || ! $this->endDate) {
            return 'Periode belum ditentukan';
        }

        if ($this->startDate > $this->endDate) {
            return 'Periode tidak valid';
        }

        $start = Carbon::parse($this->startDate)
            ->translatedFormat('d F Y');

        $end = Carbon::parse($this->endDate)
            ->translatedFormat('d F Y');

        return "{$start} — {$end}";
    }
}