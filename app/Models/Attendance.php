<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'employee_id', 'tanggal', 'jam_kerja', 'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_kerja' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function getStatusAttribute(): string
    {
        if (!empty($this->keterangan)) {
            return $this->keterangan; 
        }

        if ($this->employee && $this->employee->employment_type === 'parttime') {
            return $this->jam_kerja > 0 ? 'Normal' : 'Absen';
        }

        if ($this->jam_kerja == 0) {
            return 'Absen';
        }

        return $this->jam_kerja < 8 ? 'Telat' : 'Normal';
    }
}