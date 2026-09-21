@extends('layouts.hrd')
@section('title', 'Laporan')

@section('content')
<div class="flex justify-between items-center mb-4">
    <div>
        <p class="text-lg font-semibold text-stone-800">Laporan Rekap Payroll</p>
        <p class="text-sm text-stone-500">Ringkasan penggajian seluruh periode • {{ $periods->count() }} periode tampil</p>
    </div>
</div>

<form method="GET" class="bg-white rounded-lg border border-stone-200 p-4 mb-4 flex gap-3 flex-wrap items-end">
    <div>
        <label class="text-xs font-medium text-stone-600 mb-1 block">Bulan</label>
        <select name="bulan" class="border border-stone-300 rounded-md px-3 py-2 text-sm min-w-[160px]">
            <option value="">Semua Bulan</option>
            @foreach (range(1, 12) as $m)
                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-xs font-medium text-stone-600 mb-1 block">Tahun</label>
        <select name="tahun" class="border border-stone-300 rounded-md px-3 py-2 text-sm min-w-[130px]">
            <option value="">Semua Tahun</option>
            @foreach (range(now()->year, now()->year - 3) as $y)
                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-stone-700 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-stone-800">Terapkan</button>
    @if (request('bulan') || request('tahun'))
        <a href="{{ route('reports.index') }}" class="text-sm text-stone-500 px-2 py-2 hover:text-stone-700">Reset</a>
    @endif
</form>

<div class="grid grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-lg border border-stone-200 p-4">
        <p class="text-2xl font-semibold text-stone-800">{{ $employees }}</p>
        <p class="text-xs text-stone-500">Total Karyawan</p>
    </div>
    <div class="bg-white rounded-lg border border-stone-200 p-4">
        <p class="text-2xl font-semibold text-rose-700">Rp {{ number_format($grandTotal['gaji_pokok'] + $grandTotal['tunjangan'], 0, ',', '.') }}</p>
        <p class="text-xs text-stone-500">Total Gaji Bruto</p>
    </div>
    <div class="bg-white rounded-lg border border-stone-200 p-4">
        <p class="text-2xl font-semibold text-rose-700">Rp {{ number_format($grandTotal['potongan'], 0, ',', '.') }}</p>
        <p class="text-xs text-stone-500">Total Potongan</p>
    </div>
    <div class="bg-white rounded-lg border border-stone-200 p-4">
        <p class="text-2xl font-semibold text-rose-700">Rp {{ number_format($grandTotal['gaji_bersih'], 0, ',', '.') }}</p>
        <p class="text-xs text-stone-500">Total Gaji Bersih</p>
    </div>
</div>

<div class="bg-white rounded-lg border border-stone-200 overflow-hidden">
    <div class="px-4 py-3 border-b border-stone-100 font-medium text-stone-700 text-sm">Tabel Rekap Per Periode</div>
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-stone-500 uppercase text-xs">
            <tr>
                <th class="text-left px-4 py-3">Periode</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Karyawan</th>
                <th class="text-left px-4 py-3">Gaji Bruto</th>
                <th class="text-left px-4 py-3">Potongan</th>
                <th class="text-left px-4 py-3">Gaji Bersih</th>
                <th class="text-left px-4 py-3">Detail</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse ($periods as $p)
                <tr>
                    <td class="px-4 py-3 font-medium text-stone-800">{{ $p->nama_periode }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $p->status_ringkas === 'Selesai' ? 'bg-emerald-50 text-emerald-700' : ($p->status_ringkas === 'Draft' ? 'bg-amber-50 text-amber-700' : 'bg-stone-100 text-stone-600') }}">
                            {{ $p->status_ringkas }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-left text-stone-600">{{ $p->jumlah_karyawan }}</td>
                    <td class="px-4 py-3 text-left text-stone-600">Rp {{ number_format($p->total_gaji_pokok + $p->total_tunjangan, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-left text-stone-600">Rp {{ number_format($p->total_potongan, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-left font-semibold text-stone-600">Rp {{ number_format($p->total_gaji_bersih, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-left">
                        <a href="{{ route('payroll.show', $p) }}" class="text-emerald-700 hover:underline">Lihat &rarr;</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-stone-400 text-sm">Belum ada data periode.</td></tr>
            @endforelse
        </tbody>
        @if ($periods->isNotEmpty())
        <tfoot class="bg-stone-50 font-semibold text-stone-800">
            <tr>
                <td class="px-4 py-3" colspan="2">Grand Total</td>
                <td class="px-4 py-3 text-left text-rose-700">{{ $grandTotal['karyawan'] }}</td>
                <td class="px-4 py-3 text-left text-rose-700">Rp {{ number_format($grandTotal['gaji_pokok'] + $grandTotal['tunjangan'], 0, ',', '.') }}</td>
                <td class="px-4 py-3 text-left text-rose-700">Rp {{ number_format($grandTotal['potongan'], 0, ',', '.') }}</td>
                <td class="px-4 py-3 text-left text-rose-700">Rp {{ number_format($grandTotal['gaji_bersih'], 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
@endsection