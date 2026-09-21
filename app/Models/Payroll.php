<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $primaryKey = 'payroll_id';

    protected $fillable = [
        'employee_id', 'period_id', 'gaji_pokok', 'tunjangan', 'potongan', 'gaji_bersih', 'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function period()
    {
        return $this->belongsTo(PayrollPeriod::class, 'period_id', 'period_id');
    }

    public function details()
    {
        return $this->hasMany(PayrollDetail::class, 'payroll_id', 'payroll_id');
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class, 'payroll_id', 'payroll_id');
    }
}