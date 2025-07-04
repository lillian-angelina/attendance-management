<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Attendance;

class AttendanceBreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_correction_request_id',
        'rest_start_time',
        'rest_end_time',
    ];

    public function correctionRequest()
    {
        return $this->belongsTo(Attendance::class);
    }
}
