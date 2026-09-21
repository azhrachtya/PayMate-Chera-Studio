@extends('layouts.hrd')
@section('title', 'Edit Periode Payroll')

@section('content')
<form method="POST" action="{{ route('payroll-periode.update', $period) }}" class="bg-white rounded-lg border border-stone-200 p-6 max-w-lg space-y-4">
    @csrf @method('PUT')
    @include('payroll.periode._form')
    <button type="submit" class="bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-emerald-800">
        Perbarui Periode
    </button>
</form>
@endsection