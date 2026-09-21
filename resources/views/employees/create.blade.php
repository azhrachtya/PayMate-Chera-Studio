@extends('layouts.hrd')
@section('title', 'Tambah Karyawan')

@section('content')
<form method="POST" action="{{ route('employees.store') }}" class="bg-white rounded-lg border border-stone-200 p-6 max-w-2xl space-y-4">
    @csrf
    @include('employees._form')
    <button type="submit" class="bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-emerald-800">
        Simpan Karyawan
    </button>
</form>
@endsection