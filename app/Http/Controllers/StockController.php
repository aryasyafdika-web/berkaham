<?php

namespace App\Http\Controllers;

use App\Models\ObatAnak;
use App\Models\StockHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(): View
    {
        return view('obat-anak.stok', [
            'obatAnak' => ObatAnak::with('category')->orderBy('nama_obat')->paginate(10),
            'riwayat' => StockHistory::with('obatAnak')->latest()->paginate(12, ['*'], 'riwayat'),
        ]);
    }

    public function update(Request $request, ObatAnak $obatAnak): RedirectResponse
    {
        $validated = $request->validate([
            'jenis' => ['required', 'in:masuk,keluar'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $obatAnak): void {
            $medicine = ObatAnak::query()->lockForUpdate()->findOrFail($obatAnak->id);
            $stockBefore = $medicine->stok;
            $stockAfter = $validated['jenis'] === 'masuk'
                ? $stockBefore + $validated['jumlah']
                : $stockBefore - $validated['jumlah'];

            if ($stockAfter < 0) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah stok keluar melebihi stok yang tersedia.',
                ]);
            }

            $medicine->update(['stok' => $stockAfter]);
            $medicine->stockHistories()->create([
                'jenis' => $validated['jenis'],
                'jumlah' => $validated['jumlah'],
                'stok_sebelum' => $stockBefore,
                'stok_sesudah' => $stockAfter,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);
        });

        return redirect()->route('stok-obat.index')->with('success', 'Stok obat berhasil diperbarui.');
    }
}
