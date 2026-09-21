{{-- resources/views/emails/payslip.blade.php --}}
<p>Halo {{ $employee->name }},</p>

<p>Slip gaji kamu untuk periode <strong>{{ $period->nama_periode }}</strong> sudah tersedia dan terlampir dalam email ini (format PDF).</p>

<p>File PDF diberi proteksi password. PPDF hanya bisa dibuka menggunakan password tanggal lahir dengan format ddmmyy (dua digit tanggal lahir, dua digit bulan lahir, dua digit tahun lahir).</p>

<p>Untuk pertanyaan, klarifikasi, dan masukan terkait slip gaji ini silakan hubungi HRD.</p>

<p>Terima kasih,<br>
HRD Chera Studio</p>