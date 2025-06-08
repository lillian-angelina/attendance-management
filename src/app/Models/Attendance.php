<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttendanceBreak;
use App\Models\AttendanceRequest;
use App\Models\User;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_start',
        'work_end',
        'note',
        'work_date',
        'break_time',
        'total_time',
        'is_edited',
        'status',
        'reason',
        'requested_at',
        'target_date',
    ];

    // ユーザーとのリレーション（User モデルがある前提）
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

    public function requests()
    {
        return $this->hasMany(AttendanceRequest::class);
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
