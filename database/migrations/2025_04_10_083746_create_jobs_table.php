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

            $table->text('requirement')->nullable(); // 條件要求
            $table->text('benefits')->nullable(); // 福利制度
            $table->text('contact_info')->nullable(); // 聯絡資訊（聯絡人、電話等）
            
            $table->foreignId('created_by') // 建立者（面試官／員工）
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps(); // 建立與更新時間
        });
    }

    public function down(): void {
        Schema::dropIfExists('jobs');
    }
};
