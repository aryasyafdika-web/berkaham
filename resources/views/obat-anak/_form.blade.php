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
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="kategori">Kategori</label>
        <select id="kategori" name="kategori" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            <option value="">Pilih kategori</option>
            @foreach(['Demam', 'Antiinfeksi', 'Batuk/Pilek', 'Suplement', 'Pencernaan', 'Alergi'] as $item)
                <option value="{{ $item }}" @selected(old('kategori', $obat?->kategori) === $item)>{{ $item }}</option>
            @endforeach
        </select>
        @error('kategori') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="bentuk">Bentuk Obat</label>
        <select id="bentuk" name="bentuk" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            <option value="">Pilih bentuk</option>
            @foreach(['Sirup', 'Tablet', 'Tetes', 'Salep', 'Sachet', 'Suspensi'] as $item)
                <option value="{{ $item }}" @selected(old('bentuk', $obat?->bentuk) === $item)>{{ $item }}</option>
            @endforeach
        </select>
        @error('bentuk') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="dosis">Dosis/Konsentrasi</label>
        <input id="dosis" name="dosis" value="{{ old('dosis', $obat?->dosis) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Contoh: 120 mg/5 ml">
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="stok">Jumlah Stok</label>
        <input id="stok" name="stok" type="number" min="0" value="{{ old('stok', $obat?->stok ?? 0) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        @error('stok') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="satuan">Satuan</label>
        <select id="satuan" name="satuan" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            @foreach(['botol', 'strip', 'tube', 'sachet', 'box'] as $item)
                <option value="{{ $item }}" @selected(old('satuan', $obat?->satuan ?? 'botol') === $item)>{{ ucfirst($item) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="tanggal_kedaluwarsa">Tanggal Kedaluwarsa</label>
        <input id="tanggal_kedaluwarsa" name="tanggal_kedaluwarsa" type="date" value="{{ old('tanggal_kedaluwarsa', $obat?->tanggal_kedaluwarsa?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>
    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="keterangan">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="4" class="w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Catatan tambahan...">{{ old('keterangan', $obat?->keterangan) }}</textarea>
    </div>
</div>
