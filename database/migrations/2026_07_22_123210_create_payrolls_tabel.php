<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id('payroll_id');
            $table->foreignId('employee_id')->constrained('employees', 'employee_id');
            $table->foreignId('period_id')->constrained('payroll_periods', 'period_id');
            $table->unsignedBigInteger('gaji_pokok')->default(0);
            $table->unsignedBigInteger('tunjangan')->default(0);
            $table->unsignedBigInteger('potongan')->default(0);
            $table->unsignedBigInteger('gaji_bersih')->default(0);
            $table->enum('status', ['draft', 'diproses', 'terkirim', 'gagal'])->default('draft');
            $table->timestamps();

            $table->unique(['employee_id', 'period_id']); // 1 payroll per karyawan per periode
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};