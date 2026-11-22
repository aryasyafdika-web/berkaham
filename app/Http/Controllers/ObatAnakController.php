<?php

namespace App\Http\Controllers;

use App\Models\ObatAnak;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ObatAnakController extends Controller
{
    public function index(Request $request): View
    {
        $query = ObatAnak::query();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('nama_obat', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->string('kategori')->toString());
        }

        $obatAnak = $query->latest()->paginate(8)->withQueryString();
        $kategori = ObatAnak::query()->distinct()->pluck('kategori')->sort()->values();

        return view('obat-anak.index', [
            'obatAnak' => $obatAnak,
            'kategori' => $kategori,
            'totalObat' => ObatAnak::count(),
            'stokHabis' => ObatAnak::where('stok', 0)->count(),
            'kategoriCount' => ObatAnak::distinct()->count('kategori'),
        ]);
    }

    public function create(): View
    {
        return view('obat-anak.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255'],
            'bentuk' => ['required', 'string', 'max:255'],
            'dosis' => ['nullable', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'tanggal_kedaluwarsa' => ['nullable', 'date'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'bentuk.required' => 'Bentuk obat wajib dipilih.',
            'stok.required' => 'Jumlah stok wajib diisi.',
        ]);

        ObatAnak::create($validated);

        return redirect()->route('obat-anak.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function show(ObatAnak $obatAnak): View
    {
        return view('obat-anak.show', ['obatAnak' => $obatAnak]);
    }

    public function edit(ObatAnak $obatAnak): View
    {
        return view('obat-anak.edit', ['obatAnak' => $obatAnak]);
    }

    public function update(Request $request, ObatAnak $obatAnak): RedirectResponse
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255'],
            'bentuk' => ['required', 'string', 'max:255'],
            'dosis' => ['nullable', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'tanggal_kedaluwarsa' => ['nullable', 'date'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);

        $obatAnak->update($validated);

        return redirect()->route('obat-anak.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(ObatAnak $obatAnak): RedirectResponse
    {
        $obatAnak->delete();

        return redirect()->route('obat-anak.index')->with('success', 'Data obat berhasil dihapus.');
    }
}
