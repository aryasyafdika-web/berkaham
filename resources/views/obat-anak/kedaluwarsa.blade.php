@extends('layouts.app')
@section('title', 'Pemantauan Kedaluwarsa - Apotek Berkah')
@section('page_title', 'Obat Kedaluwarsa')

@section('content')
<div class="mx-auto max-w-[1100px]">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Pemantauan Kedaluwarsa Obat</h1>
        <p class="mt-1 text-sm text-slate-500">Obat dengan tanggal kedaluwarsa 30 hari ke depan dan yang sudah melewati tanggalnya.</p>
    </div>

    <section class="mb-6 overflow-hidden rounded-2xl border border-orange-200 bg-white">
        <div class="border-b border-orange-100 bg-orange-50 p-5">
            <h2 class="font-semibold text-orange-900">Segera Kedaluwarsa (30 Hari)</h2>
        </div>
        @include('obat-anak._expiry-table', ['medicines' => $expiringMedicines, 'status' => 'Segera kedaluwarsa', 'statusClass' => 'bg-orange-100 text-orange-800'])
    </section>

    <section class="overflow-hidden rounded-2xl border border-red-200 bg-white">
        <div class="border-b border-red-100 bg-red-50 p-5">
            <h2 class="font-semibold text-red-900">Sudah Kedaluwarsa</h2>
        </div>
        @include('obat-anak._expiry-table', ['medicines' => $expiredMedicines, 'status' => 'Kedaluwarsa', 'statusClass' => 'bg-red-100 text-red-800'])
    </section>
</div>
@endsection
