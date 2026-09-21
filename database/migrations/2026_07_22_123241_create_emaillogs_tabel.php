<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('payroll_id')->constrained('payrolls', 'payroll_id')->cascadeOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->enum('status_kirim', ['berhasil', 'gagal']);
            $table->text('keterangan')->nullable(); // pesan error kalau gagal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};