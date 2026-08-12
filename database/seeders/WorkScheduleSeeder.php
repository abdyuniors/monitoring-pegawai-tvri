<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkSchedule;

class WorkScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkSchedule::query()->upsert([
            [
                'day_of_week' => 1,
                'work_start' => '08:00:00',
                'break_start' => '12:00:00',
                'break_end' => '13:00:00',
                'work_end' => '16:00:00',
                'is_active' => true,
            ],
            [
                'day_of_week' => 2,
                'work_start' => '08:00:00',
                'break_start' => '12:00:00',
                'break_end' => '13:00:00',
                'work_end' => '16:00:00',
                'is_active' => true,
            ],
            [
                'day_of_week' => 3,
                'work_start' => '08:00:00',
                'break_start' => '12:00:00',
                'break_end' => '13:00:00',
                'work_end' => '16:00:00',
                'is_active' => true,
            ],
            [
                'day_of_week' => 4,
                'work_start' => '08:00:00',
                'break_start' => '12:00:00',
                'break_end' => '13:00:00',
                'work_end' => '16:00:00',
                'is_active' => true,
            ],
            [
                'day_of_week' => 5,
                'work_start' => '08:00:00',
                'break_start' => '12:00:00',
                'break_end' => '13:30:00',
                'work_end' => '16:30:00',
                'is_active' => true,
            ],
            [
                'day_of_week' => 6,
                'work_start' => '00:00:00',
                'break_start' => '00:00:00',
                'break_end' => '00:00:00',
                'work_end' => '00:00:00',
                'is_active' => false,
            ],
            [
                'day_of_week' => 7,
                'work_start' => '00:00:00',
                'break_start' => '00:00:00',
                'break_end' => '00:00:00',
                'work_end' => '00:00:00',
                'is_active' => false,
            ],
        ], ['day_of_week'], [
            'work_start',
            'break_start',
            'break_end',
            'work_end',
            'is_active',
            'updated_at',
        ]);
    }
}
