@extends('layouts.app')
@section('title', 'Stok Obat - Apotek Berkah')
@section('page_title', 'Stok Obat')

@section('content')
<div class="mx-auto max-w-[1200px]">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Pengelolaan Stok Obat</h1>
        <p class="mt-1 text-sm text-slate-500">Catat stok masuk dan keluar. Setiap perubahan disimpan dalam riwayat.</p>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] text-left">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Nama Obat</th><th class="px-5 py-4">Kategori</th><th class="px-5 py-4">Stok Saat Ini</th><th class="px-5 py-4">Batas Minimum</th><th class="px-5 py-4">Perubahan Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($obatAnak as $obat)
                        <tr class="text-sm">
                            <td class="px-5 py-4 font-semibold text-slate-800">{{ $obat->nama_obat }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $obat->category?->nama ?? $obat->kategori }}</td>
                            <td class="px-5 py-4 font-semibold {{ $obat->stok <= $obat->stok_minimum ? 'text-amber-700' : 'text-slate-700' }}">{{ $obat->stok }} {{ $obat->satuan }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $obat->stok_minimum }} {{ $obat->satuan }}</td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('obat-anak.stok.update', $obat) }}" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    <select name="jenis" required aria-label="Jenis perubahan stok {{ $obat->nama_obat }}" class="rounded-lg border border-slate-200 px-2 py-2 text-xs">
                                        <option value="masuk">Stok Masuk</option>
                                        <option value="keluar">Stok Keluar</option>
                                    </select>
                                    <input type="number" name="jumlah" min="1" required aria-label="Jumlah stok {{ $obat->nama_obat }}" placeholder="Jumlah" class="w-24 rounded-lg border border-slate-200 px-3 py-2 text-xs">
                                    <input name="keterangan" maxlength="255" aria-label="Keterangan stok {{ $obat->nama_obat }}" placeholder="Catatan (opsional)" class="w-40 rounded-lg border border-slate-200 px-3 py-2 text-xs">
                                    <button class="rounded-lg bg-sky-500 px-3 py-2 text-xs font-semibold text-white hover:bg-sky-600">Simpan</button>
                                </form>
                                @error('jumlah') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada data obat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($obatAnak->hasPages())
            <div class="border-t border-slate-100 p-4">{{ $obatAnak->links() }}</div>
        @endif
    </section>

    <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 p-5">
            <h2 class="font-semibold text-slate-900">Riwayat Perubahan Stok</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr><th class="px-5 py-4">Waktu</th><th class="px-5 py-4">Nama Obat</th><th class="px-5 py-4">Jenis</th><th class="px-5 py-4">Perubahan</th><th class="px-5 py-4">Stok Awal → Akhir</th><th class="px-5 py-4">Keterangan</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $item)
                        <tr class="text-sm text-slate-700">
                            <td class="px-5 py-4">{{ $item->created_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4 font-medium">{{ $item->obatAnak?->nama_obat ?? 'Obat dihapus' }}</td>
                            <td class="px-5 py-4">{{ ucfirst($item->jenis) }}</td>
                            <td class="px-5 py-4">{{ $item->jumlah }}</td>
                            <td class="px-5 py-4">{{ $item->stok_sebelum }} → {{ $item->stok_sesudah }}</td>
                            <td class="px-5 py-4">{{ $item->keterangan ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">Belum ada riwayat perubahan stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayat->hasPages())
            <div class="border-t border-slate-100 p-4">{{ $riwayat->links() }}</div>
        @endif
    </section>
</div>
@endsection
