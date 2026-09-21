@extends('layouts.hrd')
@section('title', 'Import Kehadiran')

@section('content')
<h2 class="text-sm font-medium text-stone-600 mb-1">Import data kehadiran untuk:</h2>
<p class="font-semibold text-stone-800 mb-6">{{ $store->store_name }}</p>

<div class="bg-white rounded-lg border border-stone-200 p-6 max-w-lg">
    <form method="POST" action="{{ route('attendance.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="store_id" value="{{ $store->store_id }}">

        <label class="block text-xs font-medium text-stone-600 mb-1">Periode Payroll</label>
        <select name="period_id" required class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm mb-4">
            <option value="">-- Pilih Periode --</option>
            @foreach ($periods as $period)
                <option value="{{ $period->period_id }}">
                    {{ $period->nama_periode }} ({{ $period->tanggal_mulai->format('d M') }} - {{ $period->tanggal_selesai->format('d M Y') }})
                </option>
            @endforeach
        </select>

        <label class="block text-xs font-medium text-stone-600 mb-1">File Excel</label>
        <input type="file" name="file_excel" required accept=".xlsx,.xls,.csv"
               class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm mb-4">

        <button type="submit" class="bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-emerald-800">
            Upload & Import
        </button>
    </form>
</div>
@endsection