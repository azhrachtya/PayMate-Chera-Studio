@extends('layouts.hrd')
@section('title', 'Edit Karyawan')

@section('content')
<form method="POST" action="{{ route('employees.update', $employee) }}" class="bg-white rounded-lg border border-stone-200 p-6 max-w-2xl space-y-4">
    @csrf @method('PUT')
    @include('employees._form')
    <button type="submit" class="bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-emerald-800">
        Perbarui Karyawan
    </button>
</form>
@endsection