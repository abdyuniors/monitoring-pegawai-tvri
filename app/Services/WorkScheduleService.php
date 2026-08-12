<?php

namespace App\Services;

use App\Models\WorkSchedule;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class WorkScheduleService
{
    public function getSchedule(?CarbonInterface $now = null): ?WorkSchedule
    {
        $now ??= now();

        return WorkSchedule::query()
            ->where('day_of_week', $now->dayOfWeekIso)
            ->where('is_active', true)
            ->first();
    }

    public function isWorkingDay(?CarbonInterface $now = null): bool
    {
        return $this->getSchedule($now) !== null;
    }

    public function isBeforeWork(?CarbonInterface $now = null): bool
    {
        $now ??= now();

        $schedule = $this->getSchedule($now);

        if (! $schedule) {
            return false;
        }

        return $now->format('H:i:s') < $schedule->work_start;
    }

    public function isBreak(?CarbonInterface $now = null): bool
    {
        $now ??= now();

        $schedule = $this->getSchedule($now);

        if (! $schedule) {
            return false;
        }

        $currentTime = $now->format('H:i:s');

        return $currentTime >= $schedule->break_start
            && $currentTime < $schedule->break_end;
    }

    public function isAfterWork(?CarbonInterface $now = null): bool
    {
        $now ??= now();

        $schedule = $this->getSchedule($now);

        if (! $schedule) {
            return false;
        }

        return $now->format('H:i:s') >= $schedule->work_end;
    }

    public function canRecordExit(?CarbonInterface $now = null): bool
    {
        $now ??= now();

        $schedule = $this->getSchedule($now);

        if (! $schedule) {
            return false;
        }

        $currentTime = $now->format('H:i:s');

        return $currentTime >= $schedule->work_start
            && $currentTime < $schedule->work_end
            && ! $this->isBreak($now);
    }

    public function status(?CarbonInterface $now = null): array
    {
        $now ??= now();

        $schedule = $this->getSchedule($now);

        if (! $schedule) {
            return [
                'status' => 'holiday',
                'label' => 'Hari Libur',
                'message' => 'Hari ini bukan hari kerja.',
                'can_exit' => false,
            ];
        }

        if ($this->isBeforeWork($now)) {
            return [
                'status' => 'before_work',
                'label' => 'Belum Jam Kerja',
                'message' => 'Jam kerja dimulai pukul '
                    . Carbon::parse($schedule->work_start)->format('H:i')
                    . ' WITA.',
                'can_exit' => false,
            ];
        }

        if ($this->isBreak($now)) {
            return [
                'status' => 'break',
                'label' => 'Jam Istirahat',
                'message' => 'Jam kerja berikutnya dimulai pukul '
                    . Carbon::parse($schedule->break_end)->format('H:i')
                    . ' WITA.',
                'can_exit' => false,
            ];
        }

        if ($this->isAfterWork($now)) {
            return [
                'status' => 'after_work',
                'label' => 'Jam Kerja Berakhir',
                'message' => 'Jam kerja hari ini sudah berakhir.',
                'can_exit' => false,
            ];
        }

        return [
            'status' => 'working',
            'label' => 'Jam Kerja',
            'message' => 'Pencatatan pegawai keluar tersedia.',
            'can_exit' => true,
        ];
    }
}