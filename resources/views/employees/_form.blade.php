{{-- Partial form dipakai bareng oleh create.blade.php dan edit.blade.php --}}
@php $emp = $employee ?? null; @endphp

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Nama</label>
        <input type="text" name="name" value="{{ old('name', $emp->name ?? '') }}"
               class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
       <p class="text-xs text-stone-400 mt-1">Nama Lengkap sesuai KTP</p>
        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $emp->email ?? '') }}"
               class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
        <p class="text-xs text-stone-400 mt-1">Contoh: example@gmail.com</p>
        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">NIK</label>
        <input type="text" name="nik" value="{{ old('nik', $emp->nik ?? '') }}"
               class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">NPWP</label>
        <input type="text" name="npwp" value="{{ old('npwp', $emp->npwp ?? '') }}"
               class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Toko</label>
        <select id="store_id" name="store_id" class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
            @foreach ($stores as $store)
                <option value="{{ $store->store_id }}" data-umr="{{ $store->umr }}"
                        {{ old('store_id', $emp->store_id ?? '') === $store->store_id ? 'selected' : '' }}>
                    {{ $store->store_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Status Kepegawaian</label>
        <select id="employment_type" name="employment_type" class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
            <option value="fulltime" {{ old('employment_type', $emp->employment_type ?? '') === 'fulltime' ? 'selected' : '' }}>FullTime</option>
            <option value="parttime" {{ old('employment_type', $emp->employment_type ?? '') === 'parttime' ? 'selected' : '' }}>PartTime</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Jabatan</label>
        <select id="position_id" name="position_id" class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
            @foreach ($positions as $pos)
                <option value="{{ $pos->position_id }}"
                        data-parttime="{{ $pos->position_name === 'Part Time Tea Barista' ? '1' : '0' }}"
                        {{ old('position_id', $emp->position_id ?? '') == $pos->position_id ? 'selected' : '' }}>
                    {{ $pos->position_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Satuan Gaji</label>
        <select id="salary_unit" name="salary_unit" class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
            <option value="per_bulan" {{ old('salary_unit', $emp->salary_unit ?? '') === 'per_bulan' ? 'selected' : '' }}>Per Bulan</option>
            <option value="per_jam" {{ old('salary_unit', $emp->salary_unit ?? '') === 'per_jam' ? 'selected' : '' }}>Per Jam</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Rate Gaji (Rp)</label>
        <input type="number" id="salary_rate" name="salary_rate" value="{{ old('salary_rate', $emp->salary_rate ?? '') }}"
            class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
        <p class="text-xs text-stone-400 mt-1">Terisi otomatis dari UMR toko. Bisa diedit manual jika perlu.</p>
        @error('salary_rate') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Tanggal Lahir</label>
        <input type="text" name="birth_date" id="birth_date"
           value="{{ old('birth_date', $emp?->birth_date?->format('Y-m-d') ?? '') }}"
           class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm" autocomplete="off">
         <p class="text-xs text-stone-400 mt-1">Dipakai sebagai password PDF slip gaji (format ddmmyy)</p>
        @error('birth_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Tanggal Bergabung</label>
        <input type="text" name="join_date" id="join_date"
           value="{{ old('join_date', $emp?->join_date?->format('Y-m-d') ?? '') }}"
           class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm" autocomplete="off">
            @error('join_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Nama Bank</label>
        <input type="text" name="bank_name" placeholder="contoh: BCA" value="{{ old('bank_name', $emp->bank_name ?? '') }}"
            class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Nama Pemilik Rekening</label>
        <input type="text" name="account_name" value="{{ old('account_name', $emp->account_name ?? '') }}"
            class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-xs font-medium text-stone-600 mb-1">Nomor Rekening</label>
        <input type="text" name="account_number" value="{{ old('account_number', $emp->account_number ?? '') }}"
            class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
    </div>
</div>

<script>
    window.addEventListener('load', function () {
    const employmentType = document.getElementById('employment_type');
    const position = document.getElementById('position_id');
    const salaryUnit = document.getElementById('salary_unit');
    const storeSelect = document.getElementById('store_id');
    const salaryRate = document.getElementById('salary_rate');

    const semuaOption = Array.from(position.options).map(function (opt) {
        return {
            value: opt.value,
            text: opt.textContent,
            isParttime: opt.dataset.parttime === '1',
            selected: opt.selected,
        };
    });

    function hitungRateOtomatis() {
        const selectedStore = storeSelect.options[storeSelect.selectedIndex];
        const umr = parseInt(selectedStore.dataset.umr || '0', 10);
        const isParttime = employmentType.value === 'parttime';

        if (umr > 0) {
            salaryRate.value = isParttime ? Math.round(umr / 173) : umr;
        }
    }

    function terapkanFilter() {
        const isParttime = employmentType.value === 'parttime';
        const valueTerpilihSaatIni = position.value;

        position.innerHTML = '';

        const opsiCocok = semuaOption.filter(function (opt) {
            return opt.isParttime === isParttime;
        });

        opsiCocok.forEach(function (opt) {
            const el = document.createElement('option');
            el.value = opt.value;
            el.textContent = opt.text;
            el.dataset.parttime = opt.isParttime ? '1' : '0';
            position.appendChild(el);
        });

        const cocokValue = opsiCocok.find(function (opt) { return opt.value === valueTerpilihSaatIni; });
        position.value = cocokValue ? valueTerpilihSaatIni : (opsiCocok[0] ? opsiCocok[0].value : '');

        salaryUnit.value = isParttime ? 'per_jam' : 'per_bulan';

        hitungRateOtomatis();
    }

    employmentType.addEventListener('change', terapkanFilter);
    storeSelect.addEventListener('change', hitungRateOtomatis);
    terapkanFilter();

    flatpickr("#birth_date", {
        dateFormat: "Y-m-d", altInput: true, altFormat: "d/m/Y", maxDate: "today", allowInput: true,
    });
    flatpickr("#join_date", {
        dateFormat: "Y-m-d", altInput: true, altFormat: "d/m/Y", maxDate: "today", allowInput: true,
    });
});
</script>