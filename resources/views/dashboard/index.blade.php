@extends('layouts.hrd')
@section('title', 'Dashboard Chera Studio')

@section('content')
<p class="text-2xl font-semibold text-stone-900" >Selamat Datang, HRD CHERA STUDIO</p>
<p class="text-sm text-stone-600 mb-6">Ringkasan sistem penggajian Chera Studio — {{ now()->translatedFormat('l, d F Y') }}</p>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-stone-200 p-5">
        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
            </svg>
        </div>
        <p class="text-2xl font-semibold text-stone-800">{{ $totalKaryawan }}</p>
        <p class="text-xs text-stone-500">Total Karyawan ({{ $totalFulltime }} Full Time / {{ $totalParttime }} Part Time)</p>
    </div>

    <div class="bg-white rounded-lg border border-stone-200 p-5">
        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <p class="text-2xl font-semibold text-rose-700">Rp {{ number_format($totalPayrollBulanIni, 0, ',', '.') }}</p>
        <p class="text-xs text-stone-500">Payroll {{ $periodeTerbaru->nama_periode ?? '-' }}</p>
    </div>

    <div class="bg-white rounded-lg border border-stone-200 p-5">
        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l7-3 7 3z" />
            </svg>
        </div>
        <p class="text-2xl font-semibold text-stone-800">{{ $karyawanPerToko->count() }}</p>
        <p class="text-xs text-stone-500">Toko Aktif</p>
    </div>

    <div class="bg-white rounded-lg border border-stone-200 p-5">
        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
        </div>
        <p class="text-2xl font-semibold text-rose-700">Rp {{ number_format($totalKeseluruhanPayroll, 0, ',', '.') }}</p>
        <p class="text-xs text-stone-500">Total Keseluruhan Penggajian</p>
    </div>
</div>

@php
    $adaDraft = \App\Models\Payroll::where('status', 'draft')->exists();
    $jumlahDraft = \App\Models\Payroll::where('status', 'draft')->count();
@endphp
@if ($adaDraft)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6 flex justify-between items-center">
        <div>
            <p class="text-sm font-medium text-amber-800">Ada {{ $jumlahDraft }} payroll dalam status Draft</p>
            <p class="text-xs text-amber-700">Segera proses payroll agar slip gaji dapat dikirim ke karyawan.</p>
        </div>
        <a href="{{ route('payroll.index') }}" class="text-sm font-medium text-amber-800 hover:underline">Lihat &rarr;</a>
    </div>
@endif

<div class="grid grid-cols-2 gap-4">
    <div class="bg-white rounded-lg border border-stone-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-stone-100 flex justify-between items-center">
            <span class="font-medium text-stone-700 text-sm">Payroll Terkini</span>
            <a href="{{ route('payroll.index') }}" class="text-xs text-emerald-700 hover:underline">Lihat semua &rarr;</a>
        </div>
        <div class="divide-y divide-stone-100">
            @forelse (\App\Models\PayrollPeriod::orderByDesc('tanggal_mulai')->take(3)->get() as $period)
                @php $totalPeriode = \App\Models\Payroll::where('period_id', $period->period_id)->sum('gaji_bersih'); @endphp
                <a href="{{ route('payroll.show', $period) }}" class="flex justify-between items-center px-4 py-3 hover:bg-stone-50">
                    <span class="text-sm text-stone-700">{{ $period->nama_periode }}</span>
                    <span class="text-sm font-medium text-stone-800">Rp {{ number_format($totalPeriode, 0, ',', '.') }}</span>
                </a>
            @empty
                <p class="px-4 py-6 text-center text-sm text-stone-400">Belum ada periode payroll.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg border border-stone-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-stone-100 flex justify-between items-center">
            <span class="font-medium text-stone-700 text-sm">Karyawan per Toko</span>
            <a href="{{ route('employees.index') }}" class="text-xs text-emerald-700 hover:underline">Lihat semua &rarr;</a>
        </div>
        <div class="divide-y divide-stone-100">
            @forelse ($karyawanPerToko as $item)
                <div class="flex justify-between items-center px-4 py-3">
                    <span class="text-sm text-stone-700">{{ $item->store->store_name ?? 'Tanpa Toko' }}</span>
                    <span class="text-sm font-medium text-stone-800">{{ $item->jumlah }} karyawan</span>
                </div>
            @empty
                <p class="px-4 py-6 text-center text-sm text-stone-400">Belum ada data toko.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection