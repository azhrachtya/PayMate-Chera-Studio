<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKaryawan = Employee::count();
        $totalFulltime = Employee::where('employment_type', 'fulltime')->count();
        $totalParttime = Employee::where('employment_type', 'parttime')->count();

        $periodeTerbaru = PayrollPeriod::orderByDesc('tanggal_mulai')->first();

        $totalPayrollBulanIni = $periodeTerbaru
            ? Payroll::where('period_id', $periodeTerbaru->period_id)->sum('gaji_bersih')
            : 0;
 
        $totalKeseluruhanPayroll = Payroll::sum('gaji_bersih');

        $karyawanPerToko = Employee::selectRaw('store_id, count(*) as jumlah')
            ->groupBy('store_id')
            ->with('store')
            ->get();

        return view('dashboard.index', compact(
            'totalKaryawan', 'totalFulltime', 'totalParttime',
            'periodeTerbaru', 'totalPayrollBulanIni', 'totalKeseluruhanPayroll', 'karyawanPerToko'
        ));
    }
}