<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttendanceBreak;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_start',
        'rest_start',
        'rest_end',
        'work_end',
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
}
