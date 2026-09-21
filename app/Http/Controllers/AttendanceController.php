<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceImport;
use App\Models\Employee;
use App\Models\Store;
use App\Models\PayrollPeriod;
use App\Models\Attendance;
use App\Services\PayrollCalculationService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function __construct(private PayrollCalculationService $payrollService)
    {
    }

    public function index()
    {
        $stores = Store::all();
        return view('attendance.index', compact('stores'));
    }

    public function create(Store $store)
    {
        $periods = PayrollPeriod::orderByDesc('tanggal_mulai')->get();
        return view('attendance.create', compact('store', 'periods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,store_id',
            'period_id' => 'required|exists:payroll_periods,period_id',
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        $import = new AttendanceImport($request->store_id);
        Excel::import($import, $request->file('file_excel'));

        $period = PayrollPeriod::findOrFail($request->period_id);

        // Otomatis hitung payroll untuk semua karyawan di toko ini, periode ini
        $employees = Employee::where('store_id', $request->store_id)->get();
        foreach ($employees as $employee) {
            $this->payrollService->hitungUntukKaryawan($employee, $period);
        }

        return redirect()->route('payroll.show', $period)
            ->with('info', "{$import->berhasil} data kehadiran berhasil diimport, {$import->dilewati} baris dilewati. Payroll untuk {$employees->count()} karyawan otomatis dihitung.");
    }



public function show(Request $request, Store $store)
{
    $tanggalMulai = $request->query('tanggal_mulai');
    $tanggalSelesai = $request->query('tanggal_selesai');
    $cari = $request->query('cari');
    $status = $request->query('status');

    $query = Attendance::with('employee')
        ->whereHas('employee', function ($q) use ($store, $cari) {
            $q->where('store_id', $store->store_id);
            if ($cari) {
                $q->where('name', 'like', '%' . $cari . '%');
            }
        });

    if ($tanggalMulai) {
        $query->whereDate('tanggal', '>=', $tanggalMulai);
    }
    if ($tanggalSelesai) {
        $query->whereDate('tanggal', '<=', $tanggalSelesai);
    }

    if ($status) {
        $query->where(function ($q) use ($status) {
            if ($status === 'Absen') {
                // keterangan kosong, jam_kerja 0, dan bukan parttime
                $q->where(function ($qq) {
                    $qq->whereNull('keterangan')->orWhere('keterangan', '');
                })
                ->where('jam_kerja', 0)
                ->whereHas('employee', fn ($e) => $e->where('employment_type', '!=', 'parttime'));
            } elseif ($status === 'Telat') {
                $q->where(function ($qq) {
                    $qq->whereNull('keterangan')->orWhere('keterangan', '');
                })
                ->where('jam_kerja', '>', 0)
                ->where('jam_kerja', '<', 8)
                ->whereHas('employee', fn ($e) => $e->where('employment_type', '!=', 'parttime'));
            } elseif ($status === 'Normal') {
                $q->where(function ($qq) {
                    $qq->whereNull('keterangan')->orWhere('keterangan', '');
                })
                ->where(function ($qq) {
                    $qq->where('jam_kerja', '>=', 8)
                       ->orWhereHas('employee', function ($e) {
                           $e->where('employment_type', 'parttime');
                       });
                })
                ->where(function ($qq) {
                    // untuk parttime, syarat normal cukup jam_kerja > 0
                    $qq->where('jam_kerja', '>', 0);
                });
            } else {
                // status lain: OFF, PH, dsb — datang dari kolom keterangan langsung
                $q->where('keterangan', $status);
            }
        });
    }

    $attendances = $query->latest('tanggal')->paginate(20)->withQueryString();

    return view('attendance.show', compact('store', 'attendances', 'tanggalMulai', 'tanggalSelesai', 'cari', 'status'));
}
}