@php $per = $period ?? null; @endphp

<div>
    <label class="block text-xs font-medium text-stone-600 mb-1">Nama Periode</label>
    <input type="text" name="nama_periode" value="{{ old('nama_periode', $per->nama_periode ?? '') }}"
           placeholder="contoh: Agustus 2026"
           class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
    @error('nama_periode') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $per?->tanggal_mulai?->format('Y-m-d') ?? '') }}"
               class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
        @error('tanggal_mulai') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $per?->tanggal_selesai?->format('Y-m-d') ?? '') }}"
               class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
        @error('tanggal_selesai') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
</div>
<p class="text-xs text-stone-400">
    Pastikan rentang tanggal ini sesuai dengan tanggal kehadiran yang akan diimpor, karena payroll dihitung berdasarkan data kehadiran yang jatuh di dalam rentang ini.
</p>