@extends('layouts.app')
@section('title', 'Dashboard Obat Anak - Apotek Berkah')
@section('page_title', 'Dashboard')

@section('content')
<div class="mx-auto max-w-[1200px]">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Pencatatan Obat Anak</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola persediaan obat untuk kebutuhan anak</p>
        </div>
        <a href="{{ route('obat-anak.create') }}" class="flex items-center justify-center gap-2 rounded-xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white shadow-sm shadow-sky-200 hover:bg-sky-600">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Obat
        </a>
    </div>

    <div class="mt-7 grid gap-4 sm:grid-cols-3">
        <article class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <div class="grid size-11 place-items-center rounded-xl bg-sky-50 text-sky-600">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10.5 20.5 3.5 13.5a4.95 4.95 0 0 1 0-7l.? 0"/><path d="m8 16 8-8"/><circle cx="17" cy="7" r="4"/></svg>
                </div>
                <span class="rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-600">Aktif</span>
            </div>
            <p class="mt-5 text-sm text-slate-500">Total Jenis Obat</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ $totalObat }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <div class="grid size-11 place-items-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 9v4M12 17h.01"/><path d="M10.3 3.7 2.2 17.8A2 2 0 0 0 3.9 21h16.2a2 2 0 0 0 1.7-3.2L13.7 3.7a2 2 0 0 0-3.4 0Z"/></svg>
                </div>
            </div>
            <p class="mt-5 text-sm text-slate-500">Stok Habis</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ $stokHabis }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <div class="grid size-11 place-items-center rounded-xl bg-violet-50 text-violet-600">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M4 12h16M4 17h16"/><circle cx="8" cy="7" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="16" cy="17" r="1"/></svg>
                </div>
            </div>
            <p class="mt-5 text-sm text-slate-500">Kategori Obat</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ $kategoriCount }}</p>
        </article>
    </div>

    <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center">
            <form method="GET" action="{{ route('obat-anak.index') }}" class="flex flex-1 flex-col gap-3 sm:flex-row">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                    <input name="search" value="{{ request('search') }}" class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Cari nama atau kategori obat...">
                </div>
                <select name="kategori" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-600 outline-none focus:border-sky-400">
                    <option value="">Semua kategori</option>
                    @foreach($kategori as $item)
                        <option value="{{ $item }}" @selected(request('kategori') === $item)>{{ $item }}</option>
                    @endforeach
                </select>
                <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px] text-left">
                <thead class="bg-slate-50/70 text-xs font-semibold uppercase tracking-wide text-slate-400">
                    <tr>
                        <th class="px-5 py-4">Nama Obat</th>
                        <th class="px-5 py-4">Kategori</th>
                        <th class="px-5 py-4">Bentuk</th>
                        <th class="px-5 py-4">Stok</th>
                        <th class="px-5 py-4">Kedaluwarsa</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($obatAnak as $obat)
                        <tr class="text-sm hover:bg-slate-50/60">
                            <td class="px-5 py-4">
                                <a href="{{ route('obat-anak.show', $obat) }}" class="font-semibold text-slate-800 hover:text-sky-600">{{ $obat->nama_obat }}</a>
                                <p class="mt-1 text-xs text-slate-400">{{ $obat->dosis ?: '-' }}</p>
                            </td>
                            <td class="px-5 py-4"><span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">{{ $obat->kategori }}</span></td>
                            <td class="px-5 py-4 text-slate-600">{{ $obat->bentuk }}</td>
                            <td class="px-5 py-4">
                                @if($obat->stok === 0)
                                    <span class="font-semibold text-red-600">Habis</span>
                                @else
                                    <span class="font-semibold text-slate-700">{{ $obat->stok }} {{ $obat->satuan }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $obat->tanggal_kedaluwarsa?->format('d M Y') ?? '-' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('obat-anak.edit', $obat) }}" class="grid size-9 place-items-center rounded-lg text-slate-400 hover:bg-sky-50 hover:text-sky-600" title="Edit">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('obat-anak.destroy', $obat) }}" onsubmit="return confirm('Hapus data obat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="grid size-9 place-items-center rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-600" title="Hapus">
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 10v6M14 10v6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="mx-auto grid size-12 place-items-center rounded-full bg-slate-100 text-slate-400">
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                                </div>
                                <p class="mt-3 font-semibold text-slate-700">Belum ada data obat</p>
                                <p class="mt-1 text-sm text-slate-400">Tambahkan obat pertama untuk mulai pencatatan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($obatAnak->hasPages())
            <div class="border-t border-slate-100 p-4">{{ $obatAnak->links() }}</div>
        @endif
    </section>
</div>
@endsection
