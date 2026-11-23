@php
    $obat = $obatAnak ?? null;
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="nama_obat">Nama Obat</label>
        <input id="nama_obat" name="nama_obat" value="{{ old('nama_obat', $obat?->nama_obat) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Contoh: Paracetamol">
        @error('nama_obat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="category_id">Kategori Obat</label>
        <select id="category_id" name="category_id" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            <option value="">Pilih kategori</option>
            @foreach($kategori as $item)
                <option value="{{ $item->id }}" @selected((int) old('category_id', $obat?->category_id) === $item->id)>{{ $item->nama }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="bentuk">Bentuk Obat</label>
        <select id="bentuk" name="bentuk" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            <option value="">Pilih bentuk</option>
            @foreach(['Sirup', 'Tablet', 'Kapsul', 'Puyer', 'Drops', 'Salep', 'Suppositoria'] as $item)
                <option value="{{ $item }}" @selected(old('bentuk', $obat?->bentuk) === $item)>{{ $item }}</option>
            @endforeach
        </select>
        @error('bentuk') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="dosis">Dosis/Konsentrasi</label>
        <input id="dosis" name="dosis" value="{{ old('dosis', $obat?->dosis) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Contoh: 120 mg/5 ml">
        @error('dosis') <p class="mt-1 text-xs text-red-600" role="alert">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="stok">Jumlah Stok</label>
        <input id="stok" name="stok" type="number" min="0" value="{{ old('stok', $obat?->stok ?? 0) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        @error('stok') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="stok_minimum">Batas Minimum Stok</label>
        <input id="stok_minimum" name="stok_minimum" type="number" min="0" value="{{ old('stok_minimum', $obat?->stok_minimum ?? $stokMinimum) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        @error('stok_minimum') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="satuan">Satuan</label>
        <select id="satuan" name="satuan" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            @foreach(['Botol', 'Strip', 'Tablet', 'Kapsul', 'Tube', 'Sachet', 'Pcs'] as $item)
                <option value="{{ $item }}" @selected(old('satuan', $obat?->satuan ?? 'botol') === $item)>{{ ucfirst($item) }}</option>
            @endforeach
        </select>
        @error('satuan') <p class="mt-1 text-xs text-red-600" role="alert">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="tanggal_kedaluwarsa">Tanggal Kedaluwarsa</label>
        <input id="tanggal_kedaluwarsa" name="tanggal_kedaluwarsa" type="date" value="{{ old('tanggal_kedaluwarsa', $obat?->tanggal_kedaluwarsa?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        @error('tanggal_kedaluwarsa') <p class="mt-1 text-xs text-red-600" role="alert">{{ $message }}</p> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="keterangan">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="4" class="w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Catatan tambahan...">{{ old('keterangan', $obat?->keterangan) }}</textarea>
        @error('keterangan') <p class="mt-1 text-xs text-red-600" role="alert">{{ $message }}</p> @enderror
    </div>
</div>
