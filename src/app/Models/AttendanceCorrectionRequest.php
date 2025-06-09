<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attendance;
use App\Models\User;

class AttendanceCorrectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'date',
        'name',
        'requested_at',
    ];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
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
