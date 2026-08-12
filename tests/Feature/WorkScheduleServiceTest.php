<?php

namespace Tests\Feature;

use App\Models\WorkSchedule;
use App\Services\WorkScheduleService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkScheduleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (range(1, 7) as $day) {
            $isWorkday = $day <= 5;

            WorkSchedule::create([
                'day_of_week' => $day,
                'work_start' => '08:00:00',
                'break_start' => '12:00:00',
                'break_end' => $day === 5
                    ? '13:30:00'
                    : '13:00:00',
                'work_end' => $day === 5
                    ? '16:30:00'
                    : '16:00:00',
                'is_active' => $isWorkday,
            ]);
        }
    }

    public function test_monday_at_10_is_working_time(): void
    {
        $now = Carbon::create(
            2026,
            8,
            10,
            10,
            0,
            0,
            'Asia/Makassar'
        );

        $service = app(WorkScheduleService::class);

        $this->assertTrue(
            $service->canRecordExit($now)
        );

        $this->assertFalse(
            $service->isBreak($now)
        );
    }

    public function test_monday_at_1230_is_break(): void
    {
        $now = Carbon::create(
            2026,
            8,
            10,
            12,
            30,
            0,
            'Asia/Makassar'
        );

        $service = app(WorkScheduleService::class);

        $this->assertTrue(
            $service->isBreak($now)
        );

        $this->assertFalse(
            $service->canRecordExit($now)
        );
    }

    public function test_friday_at_1300_is_still_break(): void
    {
        $now = Carbon::create(
            2026,
            8,
            14,
            13,
            0,
            0,
            'Asia/Makassar'
        );

        $service = app(WorkScheduleService::class);

        $this->assertTrue(
            $service->isBreak($now)
        );

        $this->assertFalse(
            $service->canRecordExit($now)
        );
    }

    public function test_friday_at_1400_is_working_time(): void
    {
        $now = Carbon::create(
            2026,
            8,
            14,
            14,
            0,
            0,
            'Asia/Makassar'
        );

        $service = app(WorkScheduleService::class);

        $this->assertTrue(
            $service->canRecordExit($now)
        );
    }

    public function test_monday_at_1610_is_after_work(): void
    {
        $now = Carbon::create(
            2026,
            8,
            10,
            16,
            10,
            0,
            'Asia/Makassar'
        );

        $service = app(WorkScheduleService::class);

        $this->assertTrue(
            $service->isAfterWork($now)
        );

        $this->assertFalse(
            $service->canRecordExit($now)
        );
    }

    public function test_sunday_is_not_a_working_day(): void
    {
        $now = Carbon::create(
            2026,
            8,
            16,
            10,
            0,
            0,
            'Asia/Makassar'
        );

        $service = app(WorkScheduleService::class);

        $this->assertFalse(
            $service->isWorkingDay($now)
        );

        $this->assertFalse(
            $service->canRecordExit($now)
        );
    }
}