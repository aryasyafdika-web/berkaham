<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('pengaturan.index', [
            'stokMinimum' => (int) AppSetting::query()->where('key', 'batas_stok_minimum')->value('value'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'batas_stok_minimum' => ['required', 'integer', 'min:1', 'max:1000000'],
        ]);

        AppSetting::updateOrCreate(
            ['key' => 'batas_stok_minimum'],
            ['value' => (string) $validated['batas_stok_minimum']],
        );

        return redirect()->route('pengaturan.index')->with('success', 'Batas stok minimum berhasil disimpan.');
    }
}
