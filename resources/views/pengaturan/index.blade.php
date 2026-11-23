@extends('layouts.app')
@section('title', 'Pengaturan - Apotek Berkah')
@section('page_title', 'Pengaturan')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Pengaturan Persediaan</h1>
        <p class="mt-1 text-sm text-slate-500">Atur nilai stok minimum bawaan untuk obat baru.</p>
    </div>
    <form method="POST" action="{{ route('pengaturan.update') }}" class="rounded-2xl border border-slate-200 bg-white p-6">
        @csrf
        @method('PUT')
        <label for="batas_stok_minimum" class="mb-2 block text-sm font-semibold text-slate-700">Batas stok minimum bawaan</label>
        <p class="mb-3 text-sm text-slate-500">Obat akan diberi peringatan jika stok tersedia sama dengan atau kurang dari batas minimumnya.</p>
        <div class="flex flex-col gap-3 sm:flex-row">
            <input id="batas_stok_minimum" name="batas_stok_minimum" type="number" min="1" max="1000000" required value="{{ old('batas_stok_minimum', $stokMinimum) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-sky-400 sm:max-w-xs">
            <button class="rounded-xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white hover:bg-sky-600">Simpan Pengaturan</button>
        </div>
        @error('batas_stok_minimum') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
    </form>
</div>
@endsection
