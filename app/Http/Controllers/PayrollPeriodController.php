<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollPeriod;
use Illuminate\Http\Request;

class PayrollPeriodController extends Controller
{
    public function create()
    {
        return view('payroll.periode.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatePeriode($request);

        PayrollPeriod::create($data);

        return redirect()->route('payroll.index')->with('info', 'Periode payroll berhasil ditambahkan.');
    }

    public function edit(PayrollPeriod $period)
    {
        return view('payroll.periode.edit', ['period' => $period]);
    }

    public function update(Request $request, PayrollPeriod $period)
    {
        $data = $this->validatePeriode($request);

        $period->update($data);

        return redirect()->route('payroll.index')->with('info', 'Periode payroll berhasil diperbarui.');
    }

    public function destroy(PayrollPeriod $period)
    {
        $jumlahPayroll = Payroll::where('period_id', $period->period_id)->count();

        if ($jumlahPayroll > 0) {
            return back()->with('info', "Periode \"{$period->nama_periode}\" tidak bisa dihapus karena sudah punya {$jumlahPayroll} data payroll. Hapus payroll-nya terlebih dahulu.");
        }

        $period->delete();

        return redirect()->route('payroll.index')->with('info', 'Periode payroll berhasil dihapus.');
    }

    private function validatePeriode(Request $request): array
    {
        return $request->validate([
            'nama_periode' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);
    }
}