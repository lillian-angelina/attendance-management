<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttendanceBreak;
use App\Models\User;
use App\Models\AttendanceCorrectionRequest;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_start',
        'work_end',
        'work_date',
        'break_time',
        'total_time',
        'is_edited',
        'status',
        'requested_at',
        'target_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breaks()
    {
        return $this->hasMany(AttendanceBreak::class);
    }

    public function attendanceBreaks()
    {
        return $this->hasMany(AttendanceBreak::class);
    }

    public function correctionRequests()
    {
        return $this->hasMany(AttendanceCorrectionRequest::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', '承認済み');
    }

    public function scopePending($query)
    {
        return $query->where('status', '承認待ち');
    }
}
