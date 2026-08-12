<?php

namespace App\Filament\Pages;

use App\ExitStatus;
use App\Models\Employee;
use App\Models\ExitRecord;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $slug = '';

    protected string $view = 'filament.pages.dashboard';

    public function getTodayTotalProperty(): int
    {
        return ExitRecord::query()
            ->whereDate('exit_at', today())
            ->count();
    }

    public function getTodayReturnedProperty(): int
    {
        return ExitRecord::query()
            ->whereDate('exit_at', today())
            ->where('status', ExitStatus::RETURNED)
            ->count();
    }

    public function getActiveEmployeesProperty(): int
    {
        return Employee::query()
            ->where('is_active', true)
            ->count();
    }

    public function getCurrentlyOutProperty(): int
    {
        return ExitRecord::query()
            ->where('status', ExitStatus::OUT)
            ->count();
    }

    public function getActiveExitsProperty()
    {
        return ExitRecord::query()
            ->with([
                'employee.department',
                'recorder',
            ])
            ->where('status', ExitStatus::OUT)
            ->latest('exit_at')
            ->get();
    }

    public function getTodayRecordsProperty()
    {
        return ExitRecord::query()
            ->with([
                'employee.department',
            ])
            ->whereDate('exit_at', today())
            ->latest('exit_at')
            ->limit(8)
            ->get();
    }

    public function getDepartmentStatisticsProperty()
    {
        return Employee::query()
            ->where('is_active', true)
            ->with('department')
            ->get()
            ->groupBy(
                fn ($employee) =>
                    $employee->department?->name ?? 'Tanpa Departemen'
            )
            ->map(function ($employees, $department) {
                return [
                    'name' => $department,
                    'employees' => $employees->count(),
                    'out' => ExitRecord::query()
                        ->where('status', ExitStatus::OUT)
                        ->whereHas(
                            'employee',
                            fn ($query) =>
                                $query->whereIn(
                                    'id',
                                    $employees->pluck('id')
                                )
                        )
                        ->count(),
                ];
            })
            ->values();
    }

    public function getWorkStatusProperty(): array
    {
        $now = now();

        $schedule = WorkSchedule::query()
            ->where('day_of_week', $now->dayOfWeekIso)
            ->where('is_active', true)
            ->first();

        if (! $schedule) {
            return [
                'status' => 'holiday',
                'label' => 'Hari Libur',
                'message' => 'Hari ini bukan hari kerja.',
            ];
        }

        $currentTime = $now->format('H:i:s');

        if ($currentTime < $schedule->work_start) {
            return [
                'status' => 'before_work',
                'label' => 'Belum Jam Kerja',
                'message' => 'Jam kerja dimulai pukul ' .
                    Carbon::parse($schedule->work_start)->format('H:i'),
            ];
        }

        if (
            $currentTime >= $schedule->break_start &&
            $currentTime < $schedule->break_end
        ) {
            return [
                'status' => 'break',
                'label' => 'Jam Istirahat',
                'message' => 'Jam kerja berikutnya dimulai pukul ' .
                    Carbon::parse($schedule->break_end)->format('H:i'),
            ];
        }

        if ($currentTime >= $schedule->work_end) {
            return [
                'status' => 'after_work',
                'label' => 'Jam Kerja Berakhir',
                'message' => 'Jam kerja hari ini sudah berakhir.',
            ];
        }

        return [
            'status' => 'working',
            'label' => 'Jam Kerja',
            'message' => 'Pencatatan pegawai keluar tersedia.',
        ];
    }
}