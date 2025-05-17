<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーに紐づく
            $table->dateTime('work_start')->nullable();     // 出勤時間
            $table->dateTime('rest_start')->nullable();     // 休憩開始時間
            $table->dateTime('rest_end')->nullable();       // 休憩終了時間
            $table->dateTime('work_end')->nullable();       // 退勤時間
            $table->string('note')->nullable();             // メモ
            $table->date('work_date')->nullable();         // 勤務日
            $table->string('clock_in')->nullable();         // 曜日
            $table->string('clock_out')->nullable();        // 曜日
            $table->string('break_time')->nullable();        // 勤務時間
            $table->boolean('is_edited')->default(false);   // 編集フラグ
            $table->timestamps();                           // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
