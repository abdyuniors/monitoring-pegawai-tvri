<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use App\ExitStatus;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\WorkSchedule;
use App\Services\EmployeeExitService;
use Carbon\Carbon;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeExitServiceTest extends TestCase
{
    use RefreshDatabase;

    private Employee $employee;

    private User $security;

    protected function setUp(): void
    {
        parent::setUp();

        $department = Department::create([
            'name' => 'Teknik',
            'is_active' => true,
        ]);

        $this->employee = Employee::create([
            'nip' => '199001010001',
            'name' => 'Pegawai Test',
            'department_id' => $department->id,
            'is_active' => true,
        ]);

        $this->security = User::create([
            'name' => 'Security Test',
            'email' => 'security-test@example.com',
            'password' => 'password',
            'role' => 'security',
            'is_active' => true,
        ]);

        $this->createSchedules();
    }

    private function createSchedules(): void
    {
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

    public function test_employee_can_be_recorded_as_out_during_working_hours(): void
    {
        $now = Carbon::create(
            2026,
            8,
            10,
            10,
            30,
            0,
            'Asia/Makassar'
        );

        $record = app(EmployeeExitService::class)->recordExit(
            employee: $this->employee,
            purpose: 'Keperluan pribadi',
            destination: 'Bank',
            operator: $this->security,
            now: $now,
        );

        $this->assertSame(
            ExitStatus::OUT,
            $record->status
        );

        $this->assertSame(
            '2026-08-10 10:30:00',
            $record->exit_at->format('Y-m-d H:i:s')
        );
    }

    public function test_employee_cannot_be_recorded_during_lunch_break(): void
    {
        $this->expectException(DomainException::class);

        $now = Carbon::create(
            2026,
            8,
            10,
            12,
            30,
            0,
            'Asia/Makassar'
        );

        app(EmployeeExitService::class)->recordExit(
            employee: $this->employee,
            purpose: 'Keperluan pribadi',
            destination: 'Bank',
            operator: $this->security,
            now: $now,
        );
    }

    public function test_friday_lunch_break_is_until_1330(): void
    {
        $this->expectException(DomainException::class);

        $now = Carbon::create(
            2026,
            8,
            14,
            13,
            0,
            0,
            'Asia/Makassar'
        );

        app(EmployeeExitService::class)->recordExit(
            employee: $this->employee,
            purpose: 'Keperluan pribadi',
            destination: 'Bank',
            operator: $this->security,
            now: $now,
        );
    }

    public function test_employee_can_be_recorded_on_friday_after_lunch(): void
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

        $record = app(EmployeeExitService::class)->recordExit(
            employee: $this->employee,
            purpose: 'Keperluan pribadi',
            destination: 'Bank',
            operator: $this->security,
            now: $now,
        );

        $this->assertSame(
            ExitStatus::OUT,
            $record->status
        );
    }

    public function test_employee_cannot_be_recorded_after_working_hours(): void
    {
        $this->expectException(DomainException::class);

        $now = Carbon::create(
            2026,
            8,
            10,
            16,
            10,
            0,
            'Asia/Makassar'
        );

        app(EmployeeExitService::class)->recordExit(
            employee: $this->employee,
            purpose: 'Keperluan pribadi',
            destination: 'Bank',
            operator: $this->security,
            now: $now,
        );
    }

    public function test_employee_cannot_be_recorded_twice_while_still_out(): void
    {
        $service = app(EmployeeExitService::class);

        $now = Carbon::create(
            2026,
            8,
            10,
            10,
            0,
            0,
            'Asia/Makassar'
        );

        $service->recordExit(
            employee: $this->employee,
            purpose: 'Keperluan pribadi',
            destination: 'Bank',
            operator: $this->security,
            now: $now,
        );

        $this->expectException(DomainException::class);

        $service->recordExit(
            employee: $this->employee,
            purpose: 'Keperluan lain',
            destination: 'Kota',
            operator: $this->security,
            now: $now->copy()->addMinutes(10),
        );
    }

    public function test_employee_can_be_marked_as_returned(): void
    {
        $service = app(EmployeeExitService::class);

        $exitTime = Carbon::create(
            2026,
            8,
            10,
            10,
            0,
            0,
            'Asia/Makassar'
        );

        $returnTime = $exitTime->copy()->addMinutes(45);

        $record = $service->recordExit(
            employee: $this->employee,
            purpose: 'Keperluan pribadi',
            destination: 'Bank',
            operator: $this->security,
            now: $exitTime,
        );

        $record = $service->recordReturn(
            record: $record,
            operator: $this->security,
            now: $returnTime,
        );

        $this->assertSame(
            ExitStatus::RETURNED,
            $record->status
        );

        $this->assertSame(
            45,
            $record->getDurationInMinutes()
        );
    }
}
