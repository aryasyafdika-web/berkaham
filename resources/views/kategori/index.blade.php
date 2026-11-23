@extends('layouts.app')
@section('title', 'Kategori Obat - Apotek Berkah')
@section('page_title', 'Kategori Obat')

@section('content')
<div class="mx-auto max-w-[1100px]">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Kategori Obat Anak</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola kategori untuk mengelompokkan persediaan obat.</p>
    </div>

    @if(session('error'))
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('kategori-obat.store') }}" class="mb-6 grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 md:grid-cols-[1fr_1fr_auto] md:items-end">
        @csrf
        <div>
            <label for="nama" class="mb-2 block text-sm font-semibold text-slate-700">Nama Kategori</label>
            <input id="nama" name="nama" value="{{ old('nama') }}" required maxlength="255" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Contoh: Obat Demam">
            @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="keterangan" class="mb-2 block text-sm font-semibold text-slate-700">Keterangan</label>
            <input id="keterangan" name="keterangan" value="{{ old('keterangan') }}" maxlength="1000" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Catatan kategori (opsional)">
        </div>
        <button class="rounded-xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white hover:bg-sky-600">Tambah Kategori</button>
    </form>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 p-5">
            <h2 class="font-semibold text-slate-900">Daftar Kategori ({{ $categories->count() }})</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($categories as $category)
                <div class="grid gap-4 p-5 lg:grid-cols-[1fr_1.5fr_auto] lg:items-center">
                    <div>
                        <p class="font-semibold text-slate-900">{{ $category->nama }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $category->obat_anaks_count }} jenis obat</p>
                    </div>
                    <form method="POST" action="{{ route('kategori-obat.update', $category) }}" class="grid gap-3 sm:grid-cols-2">
                        @csrf
                        @method('PUT')
                        <input name="nama" value="{{ $category->nama }}" required maxlength="255" aria-label="Nama kategori {{ $category->nama }}" class="rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-sky-400">
                        <input name="keterangan" value="{{ $category->keterangan }}" maxlength="1000" aria-label="Keterangan kategori {{ $category->nama }}" placeholder="Keterangan" class="rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-sky-400">
                        <button class="sm:col-span-2 justify-self-start rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:border-sky-300 hover:text-sky-600">Simpan Perubahan</button>
                    </form>
                    <form method="POST" action="{{ route('kategori-obat.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ $category->nama }}?')">
                        @csrf
                        @method('DELETE')
                        <button @disabled($category->obat_anaks_count > 0) class="rounded-lg px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 disabled:cursor-not-allowed disabled:text-slate-300" title="{{ $category->obat_anaks_count > 0 ? 'Kategori masih digunakan obat' : 'Hapus kategori' }}">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="p-8 text-center text-sm text-slate-500">Belum ada kategori obat.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
