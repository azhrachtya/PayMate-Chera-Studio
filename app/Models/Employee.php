<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'store_id', 'position_id', 'name', 'employment_type', 'birth_date',
        'email', 'join_date', 'salary_rate', 'salary_unit',
        'bank_name', 'account_name', 'account_number', 'nik', 'npwp',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'employee_id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'employee_id', 'employee_id');
    }

    public function isFulltime(): bool
    {
        return $this->employment_type === 'fulltime';
    }

    // Password PDF slip gaji: ddmmyy dari tanggal lahir
    public function payslipPassword(): string
    {
        return $this->birth_date->format('dmy');
    }
}