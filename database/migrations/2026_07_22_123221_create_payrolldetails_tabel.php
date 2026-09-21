<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id('detail_id');
            $table->foreignId('payroll_id')->constrained('payrolls', 'payroll_id')->cascadeOnDelete();
            $table->string('nama_komponen'); // "Gaji Pokok", "Tunjangan Transport", "Pajak", "BPJS", "Potongan Telat"
            $table->enum('tipe_komponen', ['pendapatan', 'potongan']);
            $table->unsignedBigInteger('jumlah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};