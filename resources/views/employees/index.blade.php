@extends('layouts.hrd')
@section('title', 'Karyawan Chera Studio')

@section('content')
<div class="flex justify-between items-center mb-4">
    <div>
        <p class="text-lg font-semibold text-stone-800">Data Karyawan</p>
        <p class="text-sm text-stone-500">{{ $employees->total() }} karyawan terdaftar</p>
    </div>
    <a href="{{ route('employees.create') }}"
       class="bg-rose-950 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-rose-900">
        + Tambah Karyawan
    </a>
</div>

<form method="GET" class="bg-white rounded-lg border border-stone-200 p-4 mb-4 flex gap-3 flex-wrap">
    <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama..."
           class="flex-1 min-w-[220px] border border-stone-300 rounded-md px-3 py-2 text-sm">

    <select name="store_id" onchange="this.form.submit()" class="border border-stone-300 rounded-md px-3 py-2 text-sm min-w-[180px]">
        <option value="">Semua Toko</option>
        @foreach ($stores as $store)
            <option value="{{ $store->store_id }}" {{ request('store_id') == $store->store_id ? 'selected' : '' }}>{{ $store->store_name }}</option>
        @endforeach
    </select>

    <select name="position_id" onchange="this.form.submit()" class="border border-stone-300 rounded-md px-3 py-2 text-sm min-w-[160px]">
        <option value="">Semua Jabatan</option>
        @foreach ($positions as $position)
            <option value="{{ $position->position_id }}" {{ request('position_id') == $position->position_id ? 'selected' : '' }}>{{ $position->position_name }}</option>
        @endforeach
    </select>

    <select name="employment_type" onchange="this.form.submit()" class="border border-stone-300 rounded-md px-3 py-2 text-sm min-w-[140px]">
        <option value="">Semua Status</option>
        <option value="fulltime" {{ request('employment_type') === 'fulltime' ? 'selected' : '' }}>Fulltime</option>
        <option value="parttime" {{ request('employment_type') === 'parttime' ? 'selected' : '' }}>Parttime</option>
    </select>

    <button type="submit" class="bg-stone-700 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-stone-800">Cari</button>
    @if (request('nama') || request('store_id') || request('position_id') || request('employment_type'))
        <a href="{{ route('employees.index') }}" class="text-sm text-stone-500 px-2 py-2 hover:text-stone-700">Reset</a>
    @endif
</form>

<div class="bg-white rounded-lg border border-stone-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-stone-500 uppercase text-xs">
            <tr>
                <th class="text-left px-4 py-3">Karyawan</th>
                <th class="text-left px-4 py-3">Toko</th>
                <th class="text-left px-4 py-3">Jabatan</th>
                <th class="text-left px-4 py-3">Bank</th>
                <th class="text-left px-4 py-3">Rate</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse ($employees as $emp)
                <tr>
                    <td class="px-4 py-3">
                        <p class="font-medium text-stone-800">{{ $emp->name }}</p>
                        <p class="text-xs text-stone-400">{{ $emp->employee_id }} • {{ $emp->position->position_name ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3 text-stone-600">{{ $emp->store->store_name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $emp->position->position_name === 'Part Time Tea Barista' ? 'bg-pink-200 text-pink-700' : 'bg-blue-200 text-blue-700' }}">
                            {{ $emp->position->position_name ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-stone-600">
                        {{ $emp->bank_name }}<br><span class="text-xs text-stone-400">{{ $emp->account_number }}</span>
                    </td>
                    <td class="px-4 py-3 text-stone-600">
                        Rp {{ number_format($emp->salary_rate, 0, ',', '.') }} / {{ $emp->salary_unit === 'per_jam' ? 'jam' : 'bulan' }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $emp->employment_type === 'fulltime' ? 'bg-blue-200 text-blue-700' : 'bg-pink-200 text-pink-700' }}">
                            {{ ucfirst($emp->employment_type) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                        <a href="{{ route('employees.edit', $emp) }}" class="text-emerald-700 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('employees.destroy', $emp) }}" class="inline" onsubmit="return confirm('Hapus {{ $emp->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-stone-400 text-sm">Tidak ada karyawan yang cocok.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $employees->links() }}</div>
@endsection