<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceBreak extends Model
{
    protected $fillable = ['attendance_id', 'rest_start', 'rest_end'];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
