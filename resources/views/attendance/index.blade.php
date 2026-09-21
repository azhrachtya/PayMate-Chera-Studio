@extends('layouts.hrd')
@section('title', 'Kehadiran Chera Studio')

@section('content')
<div class="flex justify-between items-center mb-4">
    <div>
    <p class="text-lg font-semibold text-stone-800">Chera Studio Stores</p>
    <p class="text-sm text-stone-500">Pilih toko untuk melihat data kehadiran</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-4">
    @forelse ($stores as $store)
        @php
            $jumlahKaryawan = $store->employees()->count();
        @endphp
        <a href="{{ route('attendance.show', $store) }}"
           class="bg-white rounded-lg border border-stone-200 p-5 hover:shadow-md hover:border-rose-500 transition block">
            <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75" />
                </svg>
            </div>
            <p class="font-semibold text-stone-800">{{ $store->store_name }}</p>
            <p class="text-xs text-stone-400 mt-1">{{ $jumlahKaryawan }} karyawan</p>
        </a>
    @empty
        <div class="col-span-3 bg-white rounded-lg border border-stone-200 p-8 text-center text-stone-400 text-sm">
            Belum ada data toko.
        </div>
    @endforelse
</div>
@endsection