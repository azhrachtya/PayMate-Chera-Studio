<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    protected $primaryKey = 'detail_id';

    protected $fillable = ['payroll_id', 'nama_komponen', 'tipe_komponen', 'jumlah'];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id', 'payroll_id');
    }
}