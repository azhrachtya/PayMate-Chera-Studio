@extends('layouts.hrd')
@section('title', 'Detail Payroll - ' . $payroll->employee->name)

@section('content')
<a href="{{ route('payroll.show', $period) }}" class="text-sm text-stone-500 hover:text-stone-700 mb-4 inline-block">&larr; Kembali ke Payroll</a>

<div class="bg-white rounded-lg border border-stone-200 p-6 max-w-4xl">
    <div class="flex justify-between items-start mb-6 pb-4 border-b border-stone-100">
        <div>
            <p class="text-lg font-semibold text-stone-800">{{ $employee->name }}</p>
            <p class="text-sm text-stone-500">{{ $employee->position->position_name ?? '-' }} • {{ $employee->store->store_name ?? '-' }}</p>
            <p class="text-xs text-stone-400 mt-1">Periode: {{ $period->nama_periode }}</p>
        </div>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $payroll->status === 'terkirim' ? 'bg-emerald-50 text-emerald-700' : 'bg-stone-100 text-stone-600' }}">
            {{ ucfirst($payroll->status) }}
        </span>
    </div>

    <p class="text-xs font-semibold text-stone-500 uppercase mb-2">Rekap Kehadiran</p>
    <div class="overflow-x-auto mb-6">
        <table class="w-full text-xs border border-stone-200">
            <thead class="bg-stone-50">
                <tr>
                    <th class="text-left px-2 py-1.5 border-b border-stone-200">Tanggal</th>
                    <th class="text-left px-2 py-1.5 border-b border-stone-200">Hari</th>
                    <th class="text-right px-2 py-1.5 border-b border-stone-200">Jam Kerja</th>
                    <th class="text-left px-2 py-1.5 border-b border-stone-200">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach ($attendances as $a)
                    <tr class="{{ $a->is_weekend ? 'bg-purple-50' : '' }}">
                        <td class="px-2 py-1">{{ $a->tanggal->format('d M Y') }}</td>
                        <td class="px-2 py-1">{{ $a->tanggal->translatedFormat('l') }}</td>
                        <td class="px-2 py-1 text-right">{{ number_format($a->jam_kerja, 1) }}</td>
                        <td class="px-2 py-1">
                            @if ($a->keterangan)
                                <span class="px-1.5 py-0.5 rounded-full bg-stone-100 text-stone-600">{{ $a->keterangan }}</span>
                            @elseif ($a->jam_kerja == 0)
                                <span class="px-1.5 py-0.5 rounded-full bg-red-50 text-red-700">Absen</span>
                            @elseif ($a->jam_kerja < 8)
                                <span class="px-1.5 py-0.5 rounded-full bg-blue-50 text-blue-700">Kurang Jam</span>
                            @else
                                <span class="px-1.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700">Normal</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="border border-stone-200 rounded-md p-4">
            <p class="text-xs font-semibold text-stone-500 uppercase mb-2">Days and Hours</p>
            <div class="flex justify-between text-sm mb-1">
                <span class="text-stone-600">Work Days</span>
                <span class="font-medium text-stone-800">{{ $summary['work_days'] }} Hari</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-stone-600">Work Hours</span>
                <span class="font-medium text-stone-800">{{ number_format($summary['work_hours'], 1) }} Jam</span>
            </div>
        </div>
        <div class="border border-stone-200 rounded-md p-4">
            <p class="text-xs font-semibold text-stone-500 uppercase mb-2">Rate</p>
            <div class="flex justify-between text-sm">
                <span class="text-stone-600">{{ $employee->salary_unit === 'per_jam' ? 'Rate / Jam' : 'Gaji Pokok / Bulan' }}</span>
                <span class="font-medium text-stone-800">Rp {{ number_format($employee->salary_rate, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <p class="text-xs font-semibold text-stone-500 uppercase mb-2">Rincian Gaji</p>
    <table class="w-full text-sm mb-6">
        <tbody class="divide-y divide-stone-100">
            @foreach ($payroll->details as $d)
                <tr>
                    <td class="py-2 text-stone-600">{{ $d->nama_komponen }}</td>
                    <td class="py-2 text-right {{ $d->tipe_komponen === 'potongan' ? 'text-red-600' : 'text-stone-800' }}">
                        {{ $d->tipe_komponen === 'potongan' ? '-' : '' }}Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            <tr class="font-semibold text-stone-900 border-t-2 border-stone-300">
                <td class="py-3">Gaji Bersih</td>
                <td class="py-3 text-right bg-emerald-50">Rp {{ number_format($payroll->gaji_bersih, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="flex gap-2">
        <form method="POST" action="{{ route('payroll.proses-satu', [$period, $employee]) }}">
            @csrf
            <button type="submit" class="bg-white border border-stone-300 text-stone-700 text-sm font-medium px-4 py-2 rounded-md hover:bg-stone-50">Hitung Ulang</button>
        </form>
        <form method="POST" action="{{ route('payroll.kirim-slip', $payroll) }}">
            @csrf
            <button type="submit" class="bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-emerald-800">Kirim Slip</button>
        </form>
    </div>
</div>
@endsection