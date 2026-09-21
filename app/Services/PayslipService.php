<?php

namespace App\Services;

use App\Models\Payroll;
use App\Models\EmailLog;
use App\Mail\PayslipMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * Generate slip gaji PDF (dengan password) dan mengirimkannya ke email karyawan.
 * Password PDF = ddmmyy dari tanggal lahir karyawan (lihat Employee::payslipPassword()).
 */
class PayslipService
{
    /**
     * Generate PDF + kirim email untuk 1 payroll. Mencatat hasilnya ke EmailLog.
     */
    public function generateDanKirim(Payroll $payroll): EmailLog
    {
        $payroll->load(['employee.store', 'employee.position', 'details', 'period']);
        $employee = $payroll->employee;

        try {
            $pdfPath = $this->generatePdf($payroll);

            Mail::to($employee->email)->send(new PayslipMail($payroll, $pdfPath));

            // Hapus file sementara setelah terkirim, tidak perlu disimpan permanen di server
            Storage::delete($pdfPath);

            $payroll->update(['status' => 'terkirim']);

            return EmailLog::create([
                'payroll_id' => $payroll->payroll_id,
                'sent_at' => now(),
                'status_kirim' => 'berhasil',
                'keterangan' => 'Slip gaji terkirim ke ' . $employee->email,
            ]);
        } catch (\Exception $e) {
            $payroll->update(['status' => 'gagal']);

            return EmailLog::create([
                'payroll_id' => $payroll->payroll_id,
                'sent_at' => now(),
                'status_kirim' => 'gagal',
                'keterangan' => 'Gagal kirim: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Kirim ulang untuk payroll yang statusnya gagal (dipanggil manual oleh HRD).
     */
    public function kirimUlang(Payroll $payroll): EmailLog
    {
        return $this->generateDanKirim($payroll);
    }

    /**
     * Generate file PDF ber-password, simpan sementara di storage, kembalikan path-nya.
     */
    private function generatePdf(Payroll $payroll): string
    {
        $employee = $payroll->employee;
        $period = $payroll->period;

        $attendances = $employee->attendances()
            ->whereBetween('tanggal', [$period->tanggal_mulai, $period->tanggal_selesai])
            ->orderBy('tanggal')
            ->get()
            ->map(function ($a) {
                $a->is_weekend = $a->tanggal->isWeekend();
                return $a;
            });

        $summary = [
            'work_days' => $attendances->where('jam_kerja', '>', 0)->count(),
            'work_hours' => $attendances->sum('jam_kerja'),
        ];

        $pdf = Pdf::loadView('payslip.template', [
            'employee' => $employee,
            'store' => $employee->store,
            'position' => $employee->position,
            'period' => $period,
            'attendances' => $attendances,
            'summary' => $summary,
            'details' => $payroll->details,
            'totalPay' => $payroll->gaji_bersih,
        ]);

        // Password PDF = ddmmyy dari tanggal lahir karyawan
        $password = $employee->payslipPassword();
        $pdf->getDomPDF()->getCanvas()->get_cpdf()->setEncryption($password, '', ['print'], 128);

        $path = 'temp/payslip_' . $payroll->payroll_id . '_' . uniqid() . '.pdf';
        Storage::put($path, $pdf->output());

        return $path;
    }
}