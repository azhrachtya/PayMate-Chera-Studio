<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('attendance_id');
            $table->foreignId('employee_id')->constrained('employees', 'employee_id');
            $table->date('tanggal');
            $table->decimal('jam_kerja', 5, 2)->default(0);
            $table->string('keterangan')->nullable(); 
            $table->timestamps();

            $table->unique(['employee_id', 'tanggal']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};