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
            $table->dateTime('work_start');     // 出勤時間
            $table->dateTime('work_end')->nullable();       // 退勤時間
            $table->date('work_date');         // 勤務日
            $table->integer('break_time')->nullable();        // 勤務時間
            $table->integer('total_time')->nullable();        // 勤務時間（分など数値で保存推奨）
            $table->string('status')->nullable();
            $table->timestamp('requested_at')->nullable();  // 修正申請日時
            $table->string('target_date')->nullable(); // 任意（用途次第）
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
