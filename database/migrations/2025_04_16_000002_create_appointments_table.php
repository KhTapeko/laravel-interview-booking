<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id') // 應徵者
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('job_id') // 應徵職缺
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('time_slot_id') // 所選面試時段
                ->constrained()
                ->onDelete('cascade');

            $table->enum('status', ['pending', 'confirmed', 'cancelled']) // 預約狀態
                ->default('pending');

            $table->text('notes')->nullable(); // 備註或取消原因（選填）

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('appointments');
    }
};
