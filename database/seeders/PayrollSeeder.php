<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollPeriod;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    public function run(): void
    {
        $periods = PayrollPeriod::all();
        $employees = Employee::all();

        foreach ($periods as $period) {
            foreach ($employees as $emp) {
                if ($emp->employment_type === 'fulltime') {
                    $gajiPokok = $emp->salary_rate;
                    $tunjangan = 500000; // tunjangan transport fulltime
                } else {
                    // parttime: rate per jam x asumsi 80 jam kerja per periode
                    $jamKerja = 80;
                    $gajiPokok = $emp->salary_rate * $jamKerja;
                    $tunjangan = 0;
                }

                $potongan = $emp->employment_type === 'fulltime'
                    ? (int) round($gajiPokok * 0.02) // contoh potongan BPJS 2%
                    : 0;

                $gajiBersih = $gajiPokok + $tunjangan - $potongan;

                $payroll = Payroll::updateOrCreate(
                    ['employee_id' => $emp->employee_id, 'period_id' => $period->period_id],
                    [
                        'gaji_pokok' => $gajiPokok,
                        'tunjangan' => $tunjangan,
                        'potongan' => $potongan,
                        'gaji_bersih' => $gajiBersih,
                        'status' => 'draft',
                    ]
                );

                // Hapus detail lama biar tidak dobel kalau seeder dijalankan ulang
                PayrollDetail::where('payroll_id', $payroll->payroll_id)->delete();

                $details = [
                    ['nama_komponen' => 'Gaji Pokok', 'tipe_komponen' => 'pendapatan', 'jumlah' => $gajiPokok],
                ];

                if ($tunjangan > 0) {
                    $details[] = ['nama_komponen' => 'Tunjangan Transport', 'tipe_komponen' => 'pendapatan', 'jumlah' => $tunjangan];
                }

                if ($potongan > 0) {
                    $details[] = ['nama_komponen' => 'Potongan BPJS', 'tipe_komponen' => 'potongan', 'jumlah' => $potongan];
                }

                foreach ($details as $d) {
                    PayrollDetail::create(array_merge(['payroll_id' => $payroll->payroll_id], $d));
                }
            }
        }
    }
}