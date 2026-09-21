<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollPeriod;
use App\Services\PayrollCalculationService;
use App\Services\PayslipService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct(
        private PayrollCalculationService $payrollService,
        private PayslipService $payslipService,
    ) {
    }

    public function index()
    {
        $periods = PayrollPeriod::orderByDesc('tanggal_mulai')->get();
        return view('payroll.index', compact('periods'));
    }

    public function storePeriode(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:50|unique:payroll_periods,nama_periode',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        PayrollPeriod::create($request->only(['nama_periode', 'tanggal_mulai', 'tanggal_selesai']));

        return redirect()->route('payroll.index')->with('info', 'Periode payroll baru berhasil ditambahkan.');
    }

    public function updatePeriode(Request $request, PayrollPeriod $period)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:50|unique:payroll_periods,nama_periode,' . $period->period_id . ',period_id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $period->update($request->only(['nama_periode', 'tanggal_mulai', 'tanggal_selesai']));

        return redirect()->route('payroll.index')->with('info', 'Periode berhasil diperbarui.');
    }

    public function destroyPeriode(PayrollPeriod $period)
    {
        if (Payroll::where('period_id', $period->period_id)->exists()) {
            return redirect()->route('payroll.index')->with('info', 'Periode tidak bisa dihapus karena sudah memiliki data payroll.');
        }

        $period->delete();

        return redirect()->route('payroll.index')->with('info', 'Periode berhasil dihapus.');
    }

    public function show(Request $request, PayrollPeriod $period)
    {
        $query = Payroll::with(['employee.store', 'employee.position', 'details', 'emailLogs'])
        ->where('period_id', $period->period_id);

            if ($request->filled('nama')) {
            $query->whereHas('employee', fn ($q) => $q->where('name', 'like', '%' . $request->nama . '%'));
        }

            if ($request->filled('store_id')) {
            $query->whereHas('employee', fn ($q) => $q->where('store_id', $request->store_id));
        }

            if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->get();
        $stores = \App\Models\Store::all();

        return view('payroll.show', compact('period', 'payrolls', 'stores'));
    }

    public function proses(Request $request, PayrollPeriod $period)
    {
        // Ambil hanya karyawan yang benar-benar punya data kehadiran
        // di rentang tanggal periode ini — karyawan tanpa data sama sekali
        // dilewati, supaya tidak dianggap "hadir penuh" secara keliru.
        $employeeIds = \App\Models\Attendance::whereBetween('tanggal', [
                $period->tanggal_mulai->format('Y-m-d'),
                $period->tanggal_selesai->format('Y-m-d'),
            ])
            ->distinct()
            ->pluck('employee_id');

        $karyawan = \App\Models\Employee::whereIn('employee_id', $employeeIds)->get();

        $diproses = 0;
        foreach ($karyawan as $employee) {
            $this->payrollService->hitungUntukKaryawan($employee, $period);
            $diproses++;
        }

        $totalKaryawan = \App\Models\Employee::count();
        $dilewati = $totalKaryawan - $diproses;

        $pesan = "{$diproses} payroll berhasil diproses untuk periode {$period->nama_periode}.";
        if ($dilewati > 0) {
            $pesan .= " {$dilewati} karyawan dilewati karena belum ada data kehadiran yang diimpor pada periode ini.";
        }

        return redirect()->route('payroll.show', $period->period_id)->with('info', $pesan);
    }

    public function prosesSatu(Request $request, PayrollPeriod $period, \App\Models\Employee $employee)
    {
        $this->payrollService->hitungUntukKaryawan($employee, $period);

        return redirect()->route('payroll.show', $period->period_id)
            ->with('info', "Payroll {$employee->name} berhasil dihitung ulang.");
    }

    public function kirimSlip(Payroll $payroll)
    {
        $log = $this->payslipService->generateDanKirim($payroll);

        $pesan = $log->status_kirim === 'berhasil'
            ? "Slip gaji {$payroll->employee->name} berhasil dikirim."
            : "Gagal mengirim slip gaji {$payroll->employee->name}: {$log->keterangan}";

        return back()->with('info', $pesan);
    }

    public function kirimSemuaSlip(PayrollPeriod $period)
    {
        set_time_limit(300);
        $payrolls = Payroll::where('period_id', $period->period_id)->get();

        $berhasil = 0;
        $gagal = 0;

        foreach ($payrolls as $payroll) {
            $log = $this->payslipService->generateDanKirim($payroll);
            $log->status_kirim === 'berhasil' ? $berhasil++ : $gagal++;
        }

        return back()->with('info', "Selesai: {$berhasil} slip terkirim, {$gagal} gagal.");
    }

    public function detail(Payroll $payroll)
    {
        $payroll->load(['employee.store', 'employee.position', 'details']);
        return view('payroll.detail', compact('payroll'));
    }
}