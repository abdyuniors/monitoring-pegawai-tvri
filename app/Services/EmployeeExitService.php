<?php

namespace App\Services;

use App\ExitStatus;
use App\Models\Employee;
use App\Models\ExitRecord;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DomainException;
use Illuminate\Support\Facades\DB;

class EmployeeExitService
{
    public function __construct(
        private readonly WorkScheduleService $workScheduleService,
    ) {
    }

    /**
     * Mencatat pegawai keluar.
     */
    public function recordExit(
        Employee $employee,
        string $purpose,
        string $destination,
        User $operator,
        ?CarbonInterface $now = null,
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
        ?CarbonInterface $now = null,
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
     * Memastikan pegawai boleh dicatat keluar.
     */
    private function ensureEmployeeCanExit(
        Employee $employee,
        CarbonInterface $now,
    ): void {
        if (! $employee->is_active) {
            throw new DomainException(
                'Pegawai tidak aktif dan tidak dapat dicatat.'
            );
        }

        if (! $this->workScheduleService->isWorkingDay($now)) {
            throw new DomainException(
                'Hari ini bukan hari kerja.'
            );
        }

        if ($this->workScheduleService->isBreak($now)) {
            $schedule = $this->workScheduleService->getSchedule($now);

            throw new DomainException(
                'Saat ini merupakan jam istirahat. '
                . 'Jam kerja berikutnya dimulai pukul '
                . Carbon::parse($schedule->break_end)->format('H:i')
                . ' WITA.'
            );
        }

        if (
            $this->workScheduleService->isBeforeWork($now)
            || $this->workScheduleService->isAfterWork($now)
        ) {
            throw new DomainException(
                'Saat ini di luar jam kerja.'
            );
        }
    }
}