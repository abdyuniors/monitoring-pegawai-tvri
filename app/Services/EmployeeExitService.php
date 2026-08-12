<?php

namespace App\Services;

use App\ExitStatus;
use App\Models\Employee;
use App\Models\ExitRecord;
use App\Models\User;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use DomainException;
use Illuminate\Support\Facades\DB;

class EmployeeExitService
{
    /**
     * Mencatat pegawai keluar.
     */
    public function recordExit(
        Employee $employee,
        string $purpose,
        string $destination,
        User $operator,
        ?Carbon $now = null,
    ): ExitRecord {
        $now ??= now();

        $this->ensureEmployeeCanExit($employee, $now);

        $activeRecord = $employee->exitRecords()
            ->where('status', ExitStatus::OUT)
            ->exists();

        if ($activeRecord) {
            throw new DomainException(
                'Pegawai masih tercatat sedang berada di luar kantor.'
            );
        }

        return DB::transaction(function () use (
            $employee,
            $purpose,
            $destination,
            $operator,
            $now,
        ) {
            return ExitRecord::create([
                'employee_id' => $employee->id,
                'purpose' => trim($purpose),
                'destination' => trim($destination),
                'exit_at' => $now,
                'status' => ExitStatus::OUT,
                'recorded_by' => $operator->id,
            ]);
        });
    }

    /**
     * Mencatat pegawai kembali.
     */
    public function recordReturn(
        ExitRecord $record,
        User $operator,
        ?Carbon $now = null,
    ): ExitRecord {
        $now ??= now();

        if (! $record->isOut()) {
            throw new DomainException(
                'Transaksi ini sudah ditandai sebagai kembali.'
            );
        }

        if ($now->lt($record->exit_at)) {
            throw new DomainException(
                'Waktu kembali tidak boleh lebih awal dari waktu keluar.'
            );
        }

        $record->update([
            'return_at' => $now,
            'returned_by' => $operator->id,
            'status' => ExitStatus::RETURNED,
        ]);

        return $record->refresh();
    }

    /**
     * Memastikan pegawai boleh dicatat keluar pada waktu tertentu.
     */
    private function ensureEmployeeCanExit(
        Employee $employee,
        Carbon $now,
    ): void {
        if (! $employee->is_active) {
            throw new DomainException(
                'Pegawai tidak aktif dan tidak dapat dicatat.'
            );
        }

        $schedule = WorkSchedule::query()
            ->where('day_of_week', $now->dayOfWeekIso)
            ->where('is_active', true)
            ->first();

        if (! $schedule) {
            throw new DomainException(
                'Hari ini bukan hari kerja.'
            );
        }

        $currentTime = $now->format('H:i:s');

        if (
            $currentTime < $schedule->work_start ||
            $currentTime >= $schedule->work_end
        ) {
            throw new DomainException(
                'Saat ini di luar jam kerja.'
            );
        }

        if (
            $currentTime >= $schedule->break_start &&
            $currentTime < $schedule->break_end
        ) {
            throw new DomainException(
                'Saat ini merupakan jam istirahat.'
            );
        }
    }
}