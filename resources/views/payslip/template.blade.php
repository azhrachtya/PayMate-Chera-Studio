<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $employee->name }}</title>
    <style>
        @page {
            margin: 24px 28px;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1c1c1c;
        }

        /* ===== Header ===== */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-cell {
            width: 130px;
        }
        .logo-cell img {
            width: 120px;
            height: 120px;
            object-fit: contain;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .company-sub {
            font-size: 15px;
            font-style: italic;
            color: #444;
            margin: 2px 0 0;
        }
        .header-email {
            text-align: right;
            font-size: 11px;
            color: #444;
        }

        /* ===== Info karyawan ===== */
        .info-table {
            width: 100%;
            border: 1px solid #333;
            border-collapse: collapse;
            font-size: 10px;
        }
        .info-table td {
            padding: 4px 10px;
        }
        .info-label {
            color: #333;
            width: 110px;
        }
        .info-label b {
            font-weight: bold;
        }

        /* ===== Attendance grid ===== */
        .attendance-title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #333;
            border-bottom: none;
            padding: 9px;
            margin-top: 12px;
        }
        table.grid {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        table.grid td, table.grid th {
            border: 1px solid #333;
            text-align: center;
            padding: 3px 0;
            font-size: 8px;
        }
        .grid-day { background-color: #f2f2f2; font-weight: bold; }
        .grid-date { background-color: #ffffff; color: #555; }
        .grid-weekend { background-color: #e6d9f2; }
        .grid-off { background-color: #e2e2e2; color: #777; }
        .grid-hours { font-weight: bold; color: #1c1c1c; }
        .grid-partial { background-color: #dbe9f7; }
        .grid-absen { background-color: #fbdede; color: #b42318; }

        /* ===== Bawah: Days/Hours, Rates, Totals ===== */
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        table.bottom-table td {
            border: 1px solid #333;
            vertical-align: top;
            padding: 8px 10px;
            font-size: 10px;
        }
        .bottom-title {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 6px;
            display: block;
        }
        .bottom-table table.inner {
            width: 100%;
            font-size: 10px;
        }
        .bottom-table table.inner td {
            border: none;
            padding: 2px 0;
        }
        .total-highlight {
            background-color: #d9ecd3;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #333;
            padding-top: 6px;
        }
        .right { text-align: right; }
        .footer-note {
            margin-top: 16px;
            font-size: 8px;
            color: #999;
        }
    </style>
</head>
<body>

    {{-- ===== Header: logo + nama perusahaan ===== --}}
    <table class="header-table">
        <tr>
            
            @php
                $logoPath = public_path('images/logo-chera-studio.png');
                $logoData = file_exists($logoPath)
                    ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                    : null;
            @endphp
            <td class="logo-cell">
                @if ($logoData)
                    <img src="{{ $logoData }}" alt="Logo">
                @endif
            </td>
            <td>
                <p class="company-name">Chera Studio</p>
                <p class="company-sub">Slip Gaji Karyawan</p>
            </td>
            <td class="header-email">{{ $employee->email }}</td>
        </tr>
    </table>

    {{-- ===== Informasi karyawan ===== --}}
    <table class="info-table">
        <tr>
            <td class="info-label"><b>Nama Karyawan</b></td>
            <td>{{ $employee->name }}</td>
            <td class="info-label"><b>Periode</b></td>
            <td colspan="3">{{ $period->nama_periode }}</td>
        <tr>
            <td class="info-label"><b>ID Karyawan</b></td>
            <td>{{ $employee->employee_id }}</td>
            <td class="info-label"><b>Toko</b></td>
            <td colspan="3">{{ $store->store_name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label"><b>Jabatan</b></td>
            <td>{{ $position->position_name ?? '-' }}</td>
            <td class="info-label"><b>Tanggal Bayar</b></td>
            <td colspan="3">{{ now()->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="info-label"><b>Status</b></td>
            <td>{{ ucfirst($employee->employment_type) }}</td>
            <td class="info-label"><b>Rekening</b></td>
            <td colspan="3">{{ $employee->account_name }} <br> {{ $employee->bank_name }} {{ $employee->account_number }} </br> </td>
        </tr>
        <tr>
            <td class="info-label"><b>NIK</b></td>
            <td>{{ $employee->nik }}</td>
            <td class="info-label"><b>NPWP</b></td>
            <td colspan="3">{{ $employee->npwp ?? '-' }}</td>
        </tr>
        </tr>
    </table>

    {{-- ===== Grid kehadiran (gaya kalender horizontal) ===== --}}
    <div class="attendance-title">ATTENDANCE</div>
    <table class="grid">
        <tr>
            @foreach ($attendances as $a)
                <td class="grid-day {{ $a->is_weekend ? 'grid-weekend' : '' }}">
                    {{ $a->tanggal->translatedFormat('D') }}
                </td>
            @endforeach
        </tr>
        <tr>
            @foreach ($attendances as $a)
                <td class="grid-date {{ $a->is_weekend ? 'grid-weekend' : '' }}">
                    {{ $a->tanggal->format('d') }}
                </td>
            @endforeach
        </tr>
        <tr>
            @foreach ($attendances as $a)
                @php
                    $cellClass = 'grid-hours';
                    if ($a->keterangan) {
                        $cellClass .= ' grid-off';
                    } elseif ($a->jam_kerja == 0) {
                        $cellClass .= ' grid-absen';
                    } elseif ($a->jam_kerja < 8) {
                        $cellClass .= ' grid-partial';
                    }
                @endphp
                <td class="{{ $cellClass }}">
                    {{ $a->keterangan ? strtoupper(substr($a->keterangan, 0, 3)) : number_format($a->jam_kerja, 1) }}
                </td>
            @endforeach
        </tr>
    </table>

    {{-- ===== Days/Hours, Rates, Totals ===== --}}
    <table class="bottom-table">
        <tr>
            <td style="width: 33%;">
                <span class="bottom-title">Days and Hours</span>
                <table class="inner">
                    <tr>
                        <td>Work Days</td>
                        <td class="right">{{ $summary['work_days'] }} Days</td>
                    </tr>
                    <tr>
                        <td>Work Hours</td>
                        <td class="right">{{ number_format($summary['work_hours'], 1) }} Hours</td>
                    </tr>
                </table>
            </td>
            <td style="width: 33%;">
                <span class="bottom-title">Rate</span>
                <table class="inner">
                    <tr>
                        <td>{{ $employee->salary_unit === 'per_jam' ? 'Rate / Jam' : 'Gaji Pokok / Bulan' }}</td>
                        <td class="right">Rp {{ number_format($employee->salary_rate, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 34%;">
                <span class="bottom-title">Totals</span>
                <table class="inner">
                    @foreach ($details as $d)
                        <tr>
                            <td>{{ $d->nama_komponen }}</td>
                            <td class="right">
                                {{ $d->tipe_komponen === 'potongan' ? '-' : '' }}Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td><b>Total Pay</b></td>
                        <td class="right total-highlight"><b>Rp {{ number_format($totalPay, 0, ',', '.') }}</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini dibuat otomatis oleh sistem PayMate Chera Studio dan dilindungi kata sandi. Mohon simpan slip gaji ini sebagai arsip pribadi.
    </div>

</body>
</html>