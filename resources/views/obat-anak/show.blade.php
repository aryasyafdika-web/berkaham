@extends('layouts.app')
@section('title', $obatAnak->nama_obat.' - Apotek Berkah')
@section('page_title', 'Detail Obat Anak')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('obat-anak.index') }}" class="flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-sky-600">
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
        </a>
        <a href="{{ route('obat-anak.edit', $obatAnak) }}" class="rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white">Edit Obat</a>
    </div>
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-6">
            <p class="text-xs font-bold uppercase tracking-wider text-sky-600">{{ $obatAnak->kategori }}</p>
            <h1 class="mt-2 text-2xl font-bold text-slate-900">{{ $obatAnak->nama_obat }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $obatAnak->bentuk }}{{ $obatAnak->dosis ? ' · '.$obatAnak->dosis : '' }}</p>
        </div>
        <dl class="grid gap-px bg-slate-100 sm:grid-cols-2">
            <div class="bg-white p-6"><dt class="text-xs font-semibold uppercase text-slate-400">Stok</dt><dd class="mt-2 text-lg font-bold text-slate-800">{{ $obatAnak->stok }} {{ $obatAnak->satuan }}</dd></div>
            <div class="bg-white p-6"><dt class="text-xs font-semibold uppercase text-slate-400">Kedaluwarsa</dt><dd class="mt-2 text-lg font-bold text-slate-800">{{ $obatAnak->tanggal_kedaluwarsa?->format('d M Y') ?? '-' }}</dd></div>
        </dl>
        <div class="p-6">
            <p class="text-xs font-semibold uppercase text-slate-400">Keterangan</p>
            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $obatAnak->keterangan ?: 'Tidak ada keterangan.' }}</p>
        </div>
    </section>
</div>
@endsection
