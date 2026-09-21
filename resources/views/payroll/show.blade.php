@extends('layouts.hrd')
@section('title', 'Payroll - ' . $period->nama_periode)

@section('content')
<a href="{{ route('payroll.index') }}" class="text-sm text-stone-500 hover:text-stone-700 mb-3 inline-block">&larr; Kembali</a>

<div class="flex justify-between items-center mb-4">
    <p class="text-sm text-stone-500">{{ $payrolls->count() }} payroll pada periode ini</p>
    <div class="flex gap-2">
        <form method="POST" action="{{ route('payroll.proses', $period) }}" onsubmit="return confirm('Proses ulang payroll untuk semua karyawan pada periode ini?')">
            @csrf
            <button type="submit" class="bg-white border border-stone-300 text-stone-700 text-sm font-medium px-4 py-2 rounded-md hover:bg-stone-50">
                Proses Payroll Semua Karyawan
            </button>
        </form>
        <form method="POST" action="{{ route('payroll.kirim-semua', $period) }}" onsubmit="return confirm('Kirim slip gaji ke semua karyawan pada periode ini?')">
            @csrf
            <button type="submit" class="bg-rose-950 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-rose-900">
                Kirim Semua Slip Gaji
            </button>
        </form>
    </div>
</div>

<form method="GET" class="flex items-end gap-3 flex-wrap mb-4">
    <div>
        <label class="text-xs font-medium text-stone-600 mb-1 block">Nama Karyawan</label>
        <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama..."
               class="border border-stone-300 rounded-md px-3 py-2 text-sm">
    </div>

    <div>
        <label class="text-xs font-medium text-stone-600 mb-1 block">Toko</label>
        <select name="store_id" class="border border-stone-300 rounded-md px-3 py-2 text-sm min-w-[220px]">
            <option value="">Semua Toko</option>
            @foreach ($stores as $store)
                <option value="{{ $store->store_id }}" {{ request('store_id') == $store->store_id ? 'selected' : '' }}>
                    {{ $store->store_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="text-xs font-medium text-stone-600 mb-1 block">Status</label>
        <select name="status" class="border border-stone-300 rounded-md px-3 py-2 text-sm min-w-[200px]">
            <option value="">Semua Status</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="terkirim" {{ request('status') === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
            <option value="gagal" {{ request('status') === 'gagal' ? 'selected' : '' }}>Gagal</option>
        </select>
    </div>

    <button type="submit" class="bg-stone-700 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-stone-800">
        Terapkan
    </button>

    @if (request('nama') || request('store_id') || request('status'))
        <a href="{{ route('payroll.show', $period) }}" class="text-sm text-stone-500 hover:text-stone-700 px-2 py-2">Reset</a>
    @endif
</form>

<div class="bg-white rounded-lg border border-stone-200 overflow-hidden overflow-x-auto">
    <table class="w-full text-sm table-fixed">
        <colgroup>
            <col class="w-[16%]">
            <col class="w-[20%]">
            <col class="w-[13%]">
            <col class="w-[14%]">
            <col class="w-[13%]">
            <col class="w-[13%]">
            <col class="w-[9%]">
            <col class="w-[10%]">
        </colgroup>
        <thead class="bg-stone-50 text-stone-500 uppercase text-xs">
            <tr>
                <th class="text-left px-4 py-3">Karyawan</th>
                <th class="text-left px-4 py-3">Toko</th>
                <th class="text-left px-4 py-3">Gaji Pokok</th>
                <th class="text-right px-4 py-3">Transport/Makan</th>
                <th class="text-right px-4 py-3">Potongan</th>
                <th class="text-left px-4 py-3">Gaji Bersih</th>
                <th class="text-center px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse ($payrolls as $p)
                <tr class="hover:bg-stone-50 h-16">
                    <td class="px-4 py-4 font-medium text-stone-800 align-middle truncate">
                        <a href="{{ route('payroll.detail', $p) }}" class="hover:underline">{{ $p->employee->name }}</a>
                    </td>
                    <td class="px-4 py-4 text-stone-600 align-middle truncate" title="{{ $p->employee->store->store_name ?? '-' }}">
                        {{ $p->employee->store->store_name ?? '-' }}
                    </td>
                    <td class="px-4 py-4 text-left text-stone-600 align-middle whitespace-nowrap">Rp {{ number_format($p->gaji_pokok, 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-left text-stone-600 align-middle whitespace-nowrap">Rp {{ number_format($p->tunjangan, 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-left text-stone-600 align-middle whitespace-nowrap">Rp {{ number_format($p->potongan, 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-left font-semibold text-stone-800 align-middle whitespace-nowrap">Rp {{ number_format($p->gaji_bersih, 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-center align-middle">
                        <span class="px-2 py-0.5 rounded-full text-xs whitespace-nowrap {{ $p->status === 'terkirim' ? 'bg-emerald-50 text-emerald-700' : ($p->status === 'gagal' ? 'bg-red-50 text-red-700' : 'bg-stone-100 text-stone-600') }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-right align-middle whitespace-nowrap">
                        <form method="POST" action="{{ route('payroll.kirim-slip', $p) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-emerald-700 hover:underline text-xs">Kirim Slip</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-stone-400 text-sm">
                        Belum ada payroll untuk periode ini. Klik "Proses Payroll Semua Karyawan" untuk menghitung.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection