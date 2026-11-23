<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Category;
use App\Models\ObatAnak;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ObatAnakController extends Controller
{
    public function index(Request $request): View
    {
        $query = ObatAnak::query()->with('category');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('nama_obat', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('category_id', $request->integer('kategori'));
        }

        if ($request->filled('bentuk')) {
            $query->where('bentuk', $request->string('bentuk')->toString());
        }

        if ($request->string('urut')->toString() === 'kedaluwarsa') {
            $query->orderByRaw('tanggal_kedaluwarsa IS NULL')->orderBy('tanggal_kedaluwarsa');
        } else {
            $query->latest();
        }

        $obatAnak = $query->paginate(8)->withQueryString();
        $kategori = Category::query()->orderBy('nama')->get();
        $today = today();
        $daysAhead = $today->copy()->addDays(30);
        $stokMinimum = (int) AppSetting::query()->where('key', 'batas_stok_minimum')->value('value');
        $categoryChart = Category::query()
            ->withCount('obatAnaks')
            ->withSum('obatAnaks', 'stok')
            ->orderBy('nama')
            ->get();

        return view('obat-anak.index', [
            'obatAnak' => $obatAnak,
            'kategori' => $kategori,
            'totalObat' => ObatAnak::count(),
            'kategoriCount' => $kategori->count(),
            'totalStok' => (int) ObatAnak::sum('stok'),
            'stokMenipis' => ObatAnak::whereColumn('stok', '<=', 'stok_minimum')->count(),
            'kedaluwarsaDekat' => ObatAnak::whereDate('tanggal_kedaluwarsa', '>=', $today)
                ->whereDate('tanggal_kedaluwarsa', '<=', $daysAhead)
                ->count(),
            'kedaluwarsa' => ObatAnak::whereDate('tanggal_kedaluwarsa', '<', $today)->count(),
            'stokMinimum' => $stokMinimum,
            'categoryChart' => $categoryChart,
            'lowStockMedicines' => ObatAnak::with('category')->whereColumn('stok', '<=', 'stok_minimum')->orderBy('stok')->limit(5)->get(),
            'expiringMedicines' => ObatAnak::with('category')
                ->whereDate('tanggal_kedaluwarsa', '>=', $today)
                ->whereDate('tanggal_kedaluwarsa', '<=', $daysAhead)
                ->orderBy('tanggal_kedaluwarsa')
                ->limit(5)
                ->get(),
            'expiredMedicines' => ObatAnak::with('category')->whereDate('tanggal_kedaluwarsa', '<', $today)->orderBy('tanggal_kedaluwarsa')->limit(5)->get(),
        ]);
    }

    public function create(): View
    {
        return view('obat-anak.create', [
            'kategori' => Category::query()->orderBy('nama')->get(),
            'stokMinimum' => (int) AppSetting::query()->where('key', 'batas_stok_minimum')->value('value'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'kategori' => ['required_without:category_id', 'nullable', 'string', 'max:255'],
            'bentuk' => ['required', 'string', 'max:255'],
            'dosis' => ['nullable', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['nullable', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'tanggal_kedaluwarsa' => ['nullable', 'date'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'bentuk.required' => 'Bentuk obat wajib dipilih.',
            'stok.required' => 'Jumlah stok wajib diisi.',
        ]);

        $category = isset($validated['category_id'])
            ? Category::findOrFail($validated['category_id'])
            : Category::firstOrCreate(['nama' => $validated['kategori']]);

        $medicine = DB::transaction(function () use ($validated, $category): ObatAnak {
            $medicine = ObatAnak::create([
                ...$validated,
                'category_id' => $category->id,
                'kategori' => $category->nama,
                'stok_minimum' => $validated['stok_minimum'] ?? (int) AppSetting::query()->where('key', 'batas_stok_minimum')->value('value'),
            ]);

            if ($medicine->stok > 0) {
                $medicine->stockHistories()->create([
                    'jenis' => 'masuk',
                    'jumlah' => $medicine->stok,
                    'stok_sebelum' => 0,
                    'stok_sesudah' => $medicine->stok,
                    'keterangan' => 'Stok awal saat obat dicatat.',
                ]);
            }

            return $medicine;
        });

        return redirect()->route('obat-anak.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function show(ObatAnak $obatAnak): View
    {
        return view('obat-anak.show', ['obatAnak' => $obatAnak]);
    }

    public function edit(ObatAnak $obatAnak): View
    {
        return view('obat-anak.edit', [
            'obatAnak' => $obatAnak,
            'kategori' => Category::query()->orderBy('nama')->get(),
            'stokMinimum' => (int) AppSetting::query()->where('key', 'batas_stok_minimum')->value('value'),
        ]);
    }

    public function update(Request $request, ObatAnak $obatAnak): RedirectResponse
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'kategori' => ['required_without:category_id', 'nullable', 'string', 'max:255'],
            'bentuk' => ['required', 'string', 'max:255'],
            'dosis' => ['nullable', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['nullable', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'tanggal_kedaluwarsa' => ['nullable', 'date'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);

        $category = isset($validated['category_id'])
            ? Category::findOrFail($validated['category_id'])
            : Category::firstOrCreate(['nama' => $validated['kategori']]);
        DB::transaction(function () use ($validated, $category, $obatAnak): void {
            $medicine = ObatAnak::query()->lockForUpdate()->findOrFail($obatAnak->id);
            $stockBefore = $medicine->stok;
            $stockAfter = (int) $validated['stok'];

            $medicine->update([
                ...$validated,
                'category_id' => $category->id,
                'kategori' => $category->nama,
                'stok_minimum' => $validated['stok_minimum'] ?? $medicine->stok_minimum,
            ]);

            if ($stockAfter !== $stockBefore) {
                $medicine->stockHistories()->create([
                    'jenis' => $stockAfter > $stockBefore ? 'masuk' : 'keluar',
                    'jumlah' => abs($stockAfter - $stockBefore),
                    'stok_sebelum' => $stockBefore,
                    'stok_sesudah' => $stockAfter,
                    'keterangan' => 'Perubahan stok dari formulir edit obat.',
                ]);
            }
        });

        return redirect()->route('obat-anak.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(ObatAnak $obatAnak): RedirectResponse
    {
        $obatAnak->delete();

        return redirect()->route('obat-anak.index')->with('success', 'Data obat berhasil dihapus.');
    }

    public function kedaluwarsa(): View
    {
        $today = today();

        return view('obat-anak.kedaluwarsa', [
            'expiredMedicines' => ObatAnak::with('category')->whereDate('tanggal_kedaluwarsa', '<', $today)->orderBy('tanggal_kedaluwarsa')->get(),
            'expiringMedicines' => ObatAnak::with('category')
                ->whereDate('tanggal_kedaluwarsa', '>=', $today)
                ->whereDate('tanggal_kedaluwarsa', '<=', $today->copy()->addDays(30))
                ->orderBy('tanggal_kedaluwarsa')
                ->get(),
        ]);
    }
}
