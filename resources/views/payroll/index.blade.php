@extends('layouts.hrd')
@section('title', 'Payroll Chera Studio')

@section('content')
<div class="flex justify-between items-center mb-4">
    <div>
    <p class="text-lg font-semibold text-stone-800">Payroll Periods</p>
    <p class="text-sm text-stone-500">{{ $periods->count() }} periode payroll</p>
    </div>
    <a href="{{ route('payroll-periode.create') }}"
       class="bg-rose-950 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-rose-900">
        + Tambah Periode
    </a>
</div>

<div class="grid grid-cols-3 gap-4">
    @forelse ($periods as $p)
        @php
            $totalGaji = \App\Models\Payroll::where('period_id', $p->period_id)->sum('gaji_bersih');
            $jumlahKaryawan = \App\Models\Payroll::where('period_id', $p->period_id)->count();
            $sudahDiproses = $jumlahKaryawan > 0;
        @endphp
        <div class="bg-white rounded-lg border border-stone-200 p-5 hover:shadow-md hover:border-rose-500 transition relative">
            <a href="{{ route('payroll.show', $p) }}" class="block">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $sudahDiproses ? 'bg-rose-50 text-rose-700' : 'bg-stone-100 text-stone-500' }}">
                        {{ $sudahDiproses ? 'Diproses' : 'Belum Diproses' }}
                    </span>
                </div>
                <p class="font-semibold text-stone-800">{{ $p->nama_periode }}</p>
                <p class="text-xs text-stone-400 mb-3">{{ $p->tanggal_mulai->format('d M') }} - {{ $p->tanggal_selesai->format('d M Y') }}</p>
                <p class="text-lg font-semibold text-rose-700">Rp {{ number_format($totalGaji, 0, ',', '.') }}</p>
                <p class="text-xs text-stone-500">{{ $jumlahKaryawan }} karyawan</p>
            </a>

            <div class="flex gap-3 mt-4 pt-3 border-t border-stone-100 text-xs">
                <a href="{{ route('payroll-periode.edit', $p) }}" class="text-stone-600 hover:underline">Edit</a>
                <form method="POST" action="{{ route('payroll-periode.destroy', $p) }}"
                      onsubmit="return confirm('Hapus periode {{ $p->nama_periode }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-3 bg-white rounded-lg border border-stone-200 p-8 text-center text-stone-400 text-sm">
            Belum ada periode payroll. Klik "+ Tambah Periode" untuk membuat.
        </div>
    @endforelse
</div>
@endsection