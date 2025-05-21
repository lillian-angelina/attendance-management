<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttendanceBreak;
use App\Models\User;

class AttendanceCorrectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'note',
        'status',
        'requested_at',
    ];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 複数の休憩とのリレーション（1対多）
    public function breaks()
    {
        return $this->hasMany(AttendanceBreak::class);
    }
}