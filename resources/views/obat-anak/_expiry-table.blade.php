<div class="overflow-x-auto">
    <table class="w-full min-w-[650px] text-left">
        <thead class="text-xs font-semibold uppercase tracking-wide text-slate-500">
            <tr><th class="px-5 py-4">Nama Obat</th><th class="px-5 py-4">Kategori</th><th class="px-5 py-4">Jumlah Stok</th><th class="px-5 py-4">Tanggal Kedaluwarsa</th><th class="px-5 py-4">Status</th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($medicines as $medicine)
                <tr class="text-sm">
                    <td class="px-5 py-4 font-semibold text-slate-800">{{ $medicine->nama_obat }}</td>
                    <td class="px-5 py-4 text-slate-600">{{ $medicine->category?->nama ?? $medicine->kategori }}</td>
                    <td class="px-5 py-4 text-slate-700">{{ $medicine->stok }} {{ $medicine->satuan }}</td>
                    <td class="px-5 py-4 text-slate-600">{{ $medicine->tanggal_kedaluwarsa->format('d M Y') }}</td>
                    <td class="px-5 py-4"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ $status }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">Tidak ada obat pada status ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
