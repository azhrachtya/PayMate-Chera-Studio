<?php

namespace App\Http\Controllers;

use App\Models\PayrollPeriod;
use App\Models\Payroll;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = PayrollPeriod::query();

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_selesai', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_selesai', $request->tahun);
        }

        $periods = $query->orderByDesc('tanggal_mulai')->get()->map(function ($period) {
            $payrolls = Payroll::where('period_id', $period->period_id)->get();

            $period->jumlah_karyawan = $payrolls->count();
            $period->total_gaji_pokok = $payrolls->sum('gaji_pokok');
            $period->total_tunjangan = $payrolls->sum('tunjangan');
            $period->total_potongan = $payrolls->sum('potongan');
            $period->total_gaji_bersih = $payrolls->sum('gaji_bersih');
            $period->status_ringkas = $payrolls->isEmpty()
                ? 'Belum Diproses'
                : ($payrolls->every(fn ($p) => $p->status === 'terkirim') ? 'Selesai' : 'Draft');

            return $period;
        });

        
        $employees = Payroll::whereIn('period_id', $periods->pluck('period_id'))
            ->distinct('employee_id')
            ->count('employee_id');

        $grandTotal = [
            'karyawan' => $periods->sum('jumlah_karyawan'),
            'gaji_pokok' => $periods->sum('total_gaji_pokok'),
            'tunjangan' => $periods->sum('total_tunjangan'),
            'potongan' => $periods->sum('total_potongan'),
            'gaji_bersih' => $periods->sum('total_gaji_bersih'),
        ];

        return view('reports.index', compact('periods', 'grandTotal', 'employees'));
    }
}