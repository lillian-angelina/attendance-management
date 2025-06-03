<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Attendance;
use App\Models\User;

class AttendanceRequest extends Model
{
    use HasFactory;

        protected $fillable = [
        'admin_id',
        'attendance_id',
        'start_time',
        'end_time',
        'break_time',
        'note',
        'status',
        'requested_at',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
