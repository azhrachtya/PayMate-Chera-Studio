@extends('layouts.hrd')
@section('title', 'Kehadiran ' . $store->store_name)

@section('content')
<a href="{{ route('attendance.index') }}" class="text-sm text-stone-500 hover:text-stone-700 mb-3 inline-block">&larr; Kembali</a>

<div class="flex justify-between items-end mb-4 flex-wrap gap-3">
    <div>
        <p class="text-lg font-semibold text-stone-800">{{ $store->store_name }}</p>
        <p class="text-sm text-stone-500">{{ $attendances->total() }} data kehadiran</p>
    </div>
    <a href="{{ route('attendance.create', $store) }}"
       class="bg-rose-950 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-rose-900">
        + Import Excel
    </a>
</div>

<form method="GET" class="flex items-end gap-3 flex-wrap mb-4">
    <div>
        <label class="text-xs font-medium text-stone-600 mb-1 block">Cari Nama</label>
        <input type="text" name="cari" value="{{ $cari }}" placeholder="Nama karyawan..."
               class="border border-stone-300 rounded-md px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-xs font-medium text-stone-600 mb-1 block">Status</label>
        <select name="status" class="border border-stone-300 rounded-md px-3 py-2 text-sm min-w-[150px]">
            <option value="">Semua Status</option>
            <option value="Normal" @selected($status === 'Normal')>Normal</option>
            <option value="Telat" @selected($status === 'Telat')>Telat</option>
            <option value="Absen" @selected($status === 'Absen')>Absen</option>
            <option value="OFF" @selected($status === 'OFF')>OFF</option>
        </select>
    </div>
    <div>
        <label class="text-xs font-medium text-stone-600 mb-1 block">Dari Tanggal</label>
        <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}"
               class="border border-stone-300 rounded-md px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-xs font-medium text-stone-600 mb-1 block">Sampai Tanggal</label>
        <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}"
               class="border border-stone-300 rounded-md px-3 py-2 text-sm">
    </div>
    <button type="submit" class="bg-stone-700 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-stone-800">
        Terapkan
    </button>
    @if ($tanggalMulai || $tanggalSelesai || $cari || $status)
        <a href="{{ route('attendance.show', $store) }}" class="text-sm text-stone-500 hover:text-stone-700 px-2 py-2">Reset</a>
    @endif
</form>

<div class="bg-white rounded-lg border border-stone-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-stone-500 uppercase text-xs">
            <tr>
                <th class="text-left px-4 py-3">Tanggal</th>
                <th class="text-left px-4 py-3">Karyawan</th>
                <th class="text-right px-4 py-3">Jam Kerja</th>
                <th class="text-center px-4 py-3">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @foreach ($attendances as $a)
                <tr>
                    <td class="px-4 py-3 text-stone-600">{{ $a->tanggal->format('d M Y') }}</td>
                    <td class="px-4 py-3 font-medium text-stone-800">{{ $a->employee->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-right text-stone-600">{{ $a->jam_kerja }}</td>
                    <td class="px-4 py-3 text-center">
                        @php $status = $a->status; @endphp
                        @if ($status === 'Normal')
                            <span class="px-2 py-0.5 rounded-full text-xs bg-emerald-50 text-emerald-700">{{ $status }}</span>
                        @elseif ($status === 'Telat')
                            <span class="px-2 py-0.5 rounded-full text-xs bg-amber-50 text-amber-700">{{ $status }}</span>
                        @elseif ($status === 'Absen')
                            <span class="px-2 py-0.5 rounded-full text-xs bg-red-50 text-red-700">{{ $status }}</span>
                        @elseif (str_starts_with($status, 'PH'))
                            <span class="px-2 py-0.5 rounded-full text-xs bg-blue-50 text-blue-700">{{ $status }}</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs bg-stone-100 text-stone-600">{{ $status }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $attendances->links() }}</div>
@endsection