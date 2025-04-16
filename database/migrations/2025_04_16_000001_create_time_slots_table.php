<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_id') // 所屬職缺
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('interviewer_id') // 負責員工（面試官）
                ->constrained('users')
                ->onDelete('cascade');

            $table->dateTime('interview_time'); // 面試時間（精確到分）
            $table->integer('max_applicants')->default(1); // 該時段最大可預約人數

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('time_slots');
    }
};
