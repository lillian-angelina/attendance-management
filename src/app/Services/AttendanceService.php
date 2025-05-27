<?php

namespace App\Services;

use Carbon\Carbon;

class AttendanceService
{
    public function calculateWorkingTime($attendance)
    {
        if (!$attendance->work_start || !$attendance->work_end) {
            return 0;
        }

        $start = Carbon::parse($attendance->work_start);
        $end = Carbon::parse($attendance->work_end);
        $totalMinutes = $start->diffInMinutes($end);

        $breakMinutes = $attendance->breaks->sum(function ($break) {
            return Carbon::parse($break->rest_start_time)->diffInMinutes(Carbon::parse($break->rest_end_time));
        });

        return $totalMinutes - $breakMinutes;
    }

    public function calculateBreakTime($attendance)
    {
        if (!method_exists($attendance, 'breaks') && !isset($attendance->breaks)) {
            return 0;
        }

        $breaks = $attendance->breaks ?? $attendance->attendanceBreaks ?? collect();

        return $breaks->reduce(function ($carry, $break) {
            $start = isset($break->rest_start) ? Carbon::parse($break->rest_start) : (isset($break->rest_start_time) ? Carbon::parse($break->rest_start_time) : null);
            $end = isset($break->rest_end) ? Carbon::parse($break->rest_end) : (isset($break->rest_end_time) ? Carbon::parse($break->rest_end_time) : null);
            if ($start && $end) {
                return $carry - $end->diffInMinutes($start);
            }
            return $carry;
        }, 0);
    }
}
