<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $primaryKey = 'log_id';

    protected $fillable = ['payroll_id', 'sent_at', 'status_kirim', 'keterangan'];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id', 'payroll_id');
    }
}