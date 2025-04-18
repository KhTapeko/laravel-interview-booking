<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            $table->string('title'); // 職缺名稱
            $table->string('company')->nullable(); // 公司名稱
            $table->string('location')->nullable(); // 工作地點
            $table->text('description')->nullable(); // 工作內容（支援多段文字）

            $table->enum('interview_type', ['individual', 'group'])->default('individual'); // 面試類型
            $table->integer('duration_minutes')->default(30); // 每場面試時間（分鐘）
            $table->integer('target_applicants')->nullable(); // 預計招募人數

            $table->integer('salary_min')->nullable(); // 薪資下限
            $table->integer('salary_max')->nullable(); // 薪資上限
            $table->string('salary_note')->nullable(); // 薪資補充說明（依經驗調整等）

            $table->text('requirement')->nullable(); // 條件要求
            $table->string('experience_required')->default('不限'); // 經歷需求（如 1 年以上）
            $table->string('education_level')->default('不限'); // 學歷要求（如 大學）
            $table->text('benefits')->nullable(); // 福利制度
            $table->text('contact_info')->nullable(); // 聯絡資訊（聯絡人、電話等）

            $table->string('job_type')->default('全職'); // 職務性質（全職／兼職／派遣）
            $table->string('work_schedule')->nullable(); // 上班時段說明（如彈性制）
            $table->boolean('remote_option')->default(false); // 是否可遠端工作
            $table->boolean('travel_required')->default(false); // 是否需出差

            $table->foreignId('created_by') // 建立者（面試官／員工）
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps(); // 建立與更新時間（created_at / updated_at）
        });
    }

    public function down(): void {
        Schema::dropIfExists('jobs');
    }
};
