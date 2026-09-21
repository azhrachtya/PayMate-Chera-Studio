<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('store_id');
            $table->foreignId('position_id')->constrained('positions', 'position_id');
            $table->string('name');
            $table->enum('employment_type', ['fulltime', 'parttime']);
            $table->date('birth_date');
            $table->string('email')->unique();
            $table->date('join_date');
            $table->unsignedBigInteger('salary_rate'); // gaji pokok (fulltime) atau rate per jam (parttime)
            $table->enum('salary_unit', ['per_bulan', 'per_jam']);
            $table->string('bank_name')->nullable();      
            $table->string('account_name')->nullable();   
            $table->string('account_number')->nullable(); 
            $table->string('nik', 20);
            $table->string('npwp', 25)->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};