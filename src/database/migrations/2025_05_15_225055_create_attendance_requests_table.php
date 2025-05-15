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
        Schema::create('attendance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('break_time'); // ← string から time へ変更
            $table->string('note')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // ← enum に変更
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null'); // optional
            $table->timestamp('reviewed_at')->nullable(); // optional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_requests');
    }
};
