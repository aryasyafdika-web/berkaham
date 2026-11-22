@extends('layouts.app')
@section('title', 'Edit Obat - Apotek Berkah')
@section('page_title', 'Edit Obat Anak')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('obat-anak.index') }}" class="grid size-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-sky-600" aria-label="Kembali">
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900">Edit Data Obat</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui informasi {{ $obatAnak->nama_obat }}.</p>
        </div>
    </div>
    <form method="POST" action="{{ route('obat-anak.update', $obatAnak) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-7">
        @csrf
        @method('PUT')
        @include('obat-anak._form', ['obatAnak' => $obatAnak])
        <div class="mt-7 flex justify-end gap-3 border-t border-slate-100 pt-5">
            <a href="{{ route('obat-anak.index') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600">Batal</a>
            <button type="submit" class="rounded-xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-sky-600">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
