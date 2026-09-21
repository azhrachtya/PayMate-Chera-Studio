<?php

namespace App\Mail;

use App\Models\Payroll;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PayslipMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payroll $payroll,
        public string $pdfPath,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Slip Gaji - ' . $this->payroll->period->nama_periode,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payslip',
            with: [
                'employee' => $this->payroll->employee,
                'period' => $this->payroll->period,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath(Storage::path($this->pdfPath))
                ->as('Slip-Gaji-' . $this->payroll->employee->name . '-' . $this->payroll->period->nama_periode . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}