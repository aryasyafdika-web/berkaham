@extends('layouts.app')
@section('title', 'Laporan Obat - Apotek Berkah')
@section('page_title', 'Laporan Obat')

@section('content')
<div class="report-page mx-auto max-w-[1200px]">
    <div class="no-print mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Laporan Obat Anak</h1>
            <p class="mt-1 text-sm text-slate-500">Pilih jenis laporan dan filter persediaan Apotek Berkah.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('obat-anak.laporan.export', request()->query()) }}" class="rounded-xl border border-emerald-200 bg-white px-4 py-3 text-sm font-semibold text-emerald-700 hover:bg-emerald-50">Export Excel</a>
            <button type="button" onclick="window.print()" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cetak Laporan</button>
            <button type="button" onclick="window.print()" class="rounded-xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white hover:bg-sky-600">Download PDF</button>
        </div>
    </div>

    <form method="GET" action="{{ route('obat-anak.laporan') }}" class="no-print mb-6 grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 sm:grid-cols-2 xl:grid-cols-5">
        <div>
            <label for="jenis_laporan" class="mb-2 block text-xs font-semibold text-slate-600">Jenis Laporan</label>
            <select id="jenis_laporan" name="jenis_laporan" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                @foreach(['data' => 'Data Obat', 'stok' => 'Stok Obat', 'hampir_habis' => 'Obat Hampir Habis', 'kedaluwarsa' => 'Obat Kedaluwarsa', 'kategori' => 'Berdasarkan Kategori'] as $value => $label)
                    <option value="{{ $value }}" @selected($filters['jenis_laporan'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="category_id" class="mb-2 block text-xs font-semibold text-slate-600">Kategori</label>
            <select id="category_id" name="category_id" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">Semua kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) $filters['category_id'] === (string) $category->id)>{{ $category->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="tanggal_mulai" class="mb-2 block text-xs font-semibold text-slate-600">Kedaluwarsa Mulai</label>
            <input id="tanggal_mulai" name="tanggal_mulai" type="date" value="{{ $filters['tanggal_mulai'] }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div>
            <label for="tanggal_selesai" class="mb-2 block text-xs font-semibold text-slate-600">Kedaluwarsa Sampai</label>
            <input id="tanggal_selesai" name="tanggal_selesai" type="date" value="{{ $filters['tanggal_selesai'] }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
        </div>
        <div class="flex flex-col gap-2">
            <label for="status_stok" class="text-xs font-semibold text-slate-600">Status Stok</label>
            <select id="status_stok" name="status_stok" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="">Semua status</option>
                <option value="habis" @selected($filters['status_stok'] === 'habis')>Habis</option>
                <option value="menipis" @selected($filters['status_stok'] === 'menipis')>Menipis</option>
                <option value="tersedia" @selected($filters['status_stok'] === 'tersedia')>Tersedia</option>
            </select>
            <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Terapkan Filter</button>
        </div>
    </form>

    <section class="print-report rounded-2xl border border-slate-200 bg-white p-5 sm:p-8">
        <header class="mb-6 border-b border-slate-200 pb-5 text-center">
            <h2 class="text-xl font-bold tracking-widest text-slate-900">APOTEK BERKAH</h2>
            <p class="mt-1 text-sm text-slate-600">Pencatatan Jenis-Jenis Obat Anak</p>
            <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $title }}</h3>
            <p class="mt-1 text-xs text-slate-500">Tanggal pencetakan: {{ now()->format('d M Y H:i') }}</p>
        </header>

        @if($filters['jenis_laporan'] === 'kategori')
            <div class="overflow-x-auto">
                <table class="report-table w-full min-w-[700px] text-left">
                    <thead><tr><th>No.</th><th>Nama Kategori</th><th>Jumlah Jenis Obat</th><th>Daftar Nama Obat</th></tr></thead>
                    <tbody>
                        @forelse($categoryRows as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->nama }}</td>
                                <td>{{ $category->obat_anaks_count }}</td>
                                <td>{{ $category->obatAnaks->pluck('nama_obat')->implode(', ') ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Belum ada kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="report-table w-full min-w-[850px] text-left">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Obat</th>
                            @if($filters['jenis_laporan'] === 'data')
                                <th>Kategori</th><th>Bentuk</th><th>Dosis/Konsentrasi</th><th>Jumlah Stok</th><th>Satuan</th><th>Tanggal Kedaluwarsa</th><th>Keterangan</th>
                            @elseif($filters['jenis_laporan'] === 'stok')
                                <th>Stok Tersedia</th><th>Satuan</th><th>Status Stok</th>
                            @elseif($filters['jenis_laporan'] === 'hampir_habis')
                                <th>Jumlah Stok</th><th>Batas Minimum</th><th>Status Persediaan</th>
                            @else
                                <th>Kategori</th><th>Jumlah Stok</th><th>Tanggal Kedaluwarsa</th><th>Status Obat</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($obatAnak as $obat)
                            @php
                                $statusStok = $obat->stok === 0 ? 'Habis' : ($obat->stok <= $obat->stok_minimum ? 'Menipis' : 'Tersedia');
                                $statusKedaluwarsa = $obat->tanggal_kedaluwarsa?->isBefore(today()) ? 'Kedaluwarsa' : ($obat->tanggal_kedaluwarsa?->lte(today()->addDays(30)) ? 'Segera kedaluwarsa' : 'Aman');
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $obat->nama_obat }}</td>
                                @if($filters['jenis_laporan'] === 'data')
                                    <td>{{ $obat->category?->nama ?? $obat->kategori }}</td>
                                    <td>{{ $obat->bentuk }}</td>
                                    <td>{{ $obat->dosis ?: '-' }}</td>
                                    <td>{{ $obat->stok }}</td>
                                    <td>{{ $obat->satuan }}</td>
                                    <td>{{ $obat->tanggal_kedaluwarsa?->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $obat->keterangan ?: '-' }}</td>
                                @elseif($filters['jenis_laporan'] === 'stok')
                                    <td>{{ $obat->stok }}</td><td>{{ $obat->satuan }}</td><td>{{ $statusStok }}</td>
                                @elseif($filters['jenis_laporan'] === 'hampir_habis')
                                    <td>{{ $obat->stok }}</td><td>{{ $obat->stok_minimum }}</td><td>{{ $statusStok }}</td>
                                @else
                                    <td>{{ $obat->category?->nama ?? $obat->kategori }}</td><td>{{ $obat->stok }}</td><td>{{ $obat->tanggal_kedaluwarsa?->format('d M Y') ?? '-' }}</td><td>{{ $statusKedaluwarsa }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">Tidak ada data obat yang cocok dengan filter.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <footer class="signature mt-12 ml-auto w-64 text-center text-sm text-slate-700">
            <p>Petugas Apotek,</p>
            <div class="h-20"></div>
            <p class="border-t border-slate-400 pt-2 font-semibold">Nama / Tanda Tangan</p>
        </footer>
        <p class="no-print mt-4 text-right text-xs text-slate-400">Untuk menyimpan sebagai PDF, pilih "Save as PDF" pada dialog cetak browser.</p>
    </section>
</div>
@endsection
