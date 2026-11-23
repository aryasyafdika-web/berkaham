<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('kategori.index', [
            'categories' => Category::query()->withCount('obatAnaks')->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:categories,nama'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        Category::create($validated);

        return redirect()->route('kategori-obat.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $kategoriObat): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('categories', 'nama')->ignore($kategoriObat->id)],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        $kategoriObat->update($validated);
        $kategoriObat->obatAnaks()->update(['kategori' => $kategoriObat->nama]);

        return redirect()->route('kategori-obat.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $kategoriObat): RedirectResponse
    {
        if ($kategoriObat->obatAnaks()->exists()) {
            return redirect()->route('kategori-obat.index')
                ->with('error', 'Kategori masih digunakan obat dan tidak dapat dihapus.');
        }

        $kategoriObat->delete();

        return redirect()->route('kategori-obat.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
