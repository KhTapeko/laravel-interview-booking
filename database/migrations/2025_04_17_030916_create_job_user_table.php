<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('job_user', function (Blueprint $table) {
            $table->id();

            // 📌 關聯到 jobs 表（職缺）
            $table->foreignId('job_id')->constrained()->onDelete('cascade');

            // 📌 關聯到 users 表（使用者）
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 📌 應徵狀態：預設為 applied，也可以是 confirmed 或 rejected
            $table->string('status')->default('applied');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('job_user');
    }
};
