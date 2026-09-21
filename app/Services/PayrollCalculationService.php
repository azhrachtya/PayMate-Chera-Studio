<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use Illuminate\Support\Facades\DB;

class PayrollCalculationService
{
    private const PAJAK_PERSEN = 0.05;
    private const BPJS_PERSEN = 0.02;
    private const POTONGAN_TELAT_PER_KEJADIAN = 50000;
    private const TUNJANGAN_PER_LEVEL = [
        1 => 600000, // Store Manager
        2 => 500000, // Assistant Store Manager
        3 => 400000, // Senior Tea Barista
        4 => 300000, // Tea Barista
    ];

    public function hitungUntukKaryawan(Employee $employee, PayrollPeriod $period): Payroll
    {
        $attendances = $employee->attendances()
            ->whereBetween('tanggal', [$period->tanggal_mulai, $period->tanggal_selesai])
            ->get();

        $jumlahTelat = $attendances->filter(fn ($a) => $a->status === 'Telat')->count();
        $jumlahAbsen = $attendances->filter(fn ($a) => $a->status === 'Absen')->count();
        $totalKejadianPotongan = $jumlahTelat + $jumlahAbsen;
        $totalJamKerja = (float) $attendances->sum('jam_kerja');

        $breakdown = $employee->isFulltime()
            ? $this->hitungFulltime($employee, $totalKejadianPotongan)
            : $this->hitungParttime($employee, $totalJamKerja);

        return DB::transaction(function () use ($employee, $period, $breakdown) {
            $payroll = Payroll::updateOrCreate(
                ['employee_id' => $employee->employee_id, 'period_id' => $period->period_id],
                [
                    'gaji_pokok' => $breakdown['gaji_pokok'],
                    'tunjangan' => $breakdown['tunjangan'],
                    'potongan' => $breakdown['total_potongan'],
                    'gaji_bersih' => $breakdown['gaji_bersih'],
                    'status' => 'diproses',
                ]
            );

            PayrollDetail::where('payroll_id', $payroll->payroll_id)->delete();

            foreach ($breakdown['details'] as [$nama, $tipe, $jumlah]) {
                PayrollDetail::create([
                    'payroll_id' => $payroll->payroll_id,
                    'nama_komponen' => $nama,
                    'tipe_komponen' => $tipe,
                    'jumlah' => $jumlah,
                ]);
            }

            return $payroll->fresh('details');
        });
    }

    public function hitungUntukSemuaKaryawan(PayrollPeriod $period): array
    {
        $hasil = [];

        Employee::chunk(50, function ($employees) use ($period, &$hasil) {
            foreach ($employees as $employee) {
                $hasil[] = $this->hitungUntukKaryawan($employee, $period);
            }
        });

        return $hasil;
    }

    private function hitungFulltime(Employee $employee, int $totalKejadianPotongan): array
    {
        $gajiPokok = (int) $employee->salary_rate;
        $level = $employee->position->level ?? null;
        $tunjangan = self::TUNJANGAN_PER_LEVEL[$level] ?? 0;

        $potonganPajak = (int) round($gajiPokok * self::PAJAK_PERSEN);
        $potonganBpjs = (int) round($gajiPokok * self::BPJS_PERSEN);
        $potonganTelat = $totalKejadianPotongan * self::POTONGAN_TELAT_PER_KEJADIAN;
        $totalPotongan = $potonganPajak + $potonganBpjs + $potonganTelat;

        $gajiBersih = max($gajiPokok + $tunjangan - $totalPotongan, 0);

        $details = [
            ['Gaji Pokok', 'pendapatan', $gajiPokok],
        ];

        if ($tunjangan > 0) {
            $details[] = ['Transport/Makan', 'pendapatan', $tunjangan];
        }

        $details[] = ['Pajak', 'potongan', $potonganPajak];
        $details[] = ['BPJS', 'potongan', $potonganBpjs];
        $details[] = ['Potongan Telat/Absen', 'potongan', $potonganTelat];

        return [
            'gaji_pokok' => $gajiPokok,
            'tunjangan' => $tunjangan,
            'total_potongan' => $totalPotongan,
            'gaji_bersih' => $gajiBersih,
            'details' => $details,
        ];
    }

    private function hitungParttime(Employee $employee, float $totalJamKerja): array
    {
        $gajiPokok = (int) round($employee->salary_rate * $totalJamKerja);
        $tunjangan = 0; // parttime tidak dapat tunjangan

        $potonganPajak = (int) round($gajiPokok * self::PAJAK_PERSEN);
        $totalPotongan = $potonganPajak;

        $gajiBersih = max($gajiPokok - $totalPotongan, 0);

        return [
            'gaji_pokok' => $gajiPokok,
            'tunjangan' => $tunjangan,
            'total_potongan' => $totalPotongan,
            'gaji_bersih' => $gajiBersih,
            'details' => [
                ['Gaji (Jam Kerja x Rate)', 'pendapatan', $gajiPokok],
                ['Pajak', 'potongan', $potonganPajak],
            ],
        ];
    }
}