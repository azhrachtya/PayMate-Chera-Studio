<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollPeriod extends Model
{
    protected $primaryKey = 'period_id';

    protected $fillable = ['nama_periode', 'tanggal_mulai', 'tanggal_selesai'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'period_id', 'period_id');
    }
}