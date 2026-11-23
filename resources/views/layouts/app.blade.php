<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Apotek Berkah')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8fb] text-slate-800">
    <div class="app-shell min-h-screen lg:grid lg:grid-cols-[240px_1fr]">
        <aside class="hidden border-r border-slate-200 bg-white lg:flex lg:flex-col">
            <a href="{{ route('obat-anak.index') }}" class="flex h-20 items-center gap-3 px-7">
                <span class="grid size-10 place-items-center rounded-xl bg-sky-500 text-white">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v18M3 12h18"/><circle cx="12" cy="12" r="9"/></svg>
                </span>
                <div>
                    <p class="font-bold text-slate-900">Apotek Berkah</p>
                    <p class="text-xs text-slate-400">Pencatatan Obat Anak</p>
                </div>
            </a>
            <nav class="mt-5 flex-1 px-4">
                @foreach([
                    ['label' => 'Dashboard', 'route' => 'obat-anak.index', 'active' => 'obat-anak.index', 'icon' => 'M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z'],
                    ['label' => 'Kategori Obat', 'route' => 'kategori-obat.index', 'active' => 'kategori-obat.*', 'icon' => 'M4 5h16M4 12h16M4 19h16M7 5v.01M7 12v.01M7 19v.01'],
                    ['label' => 'Data Obat', 'route' => 'obat-anak.index', 'active' => 'obat-anak.index', 'icon' => 'M5 4h14v16H5zM8 8h8M8 12h8M8 16h5'],
                    ['label' => 'Tambah Obat', 'route' => 'obat-anak.create', 'active' => 'obat-anak.create', 'icon' => 'M12 5v14M5 12h14'],
                    ['label' => 'Stok Obat', 'route' => 'stok-obat.index', 'active' => 'stok-obat.*', 'icon' => 'M4 7h16M4 12h16M4 17h16'],
                    ['label' => 'Obat Kedaluwarsa', 'route' => 'obat-anak.kedaluwarsa', 'active' => 'obat-anak.kedaluwarsa', 'icon' => 'M12 8v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0'],
                    ['label' => 'Laporan Obat', 'route' => 'obat-anak.laporan', 'active' => 'obat-anak.laporan', 'icon' => 'M7 3h7l5 5v13H7zM14 3v6h6M9 13h6M9 17h6'],
                    ['label' => 'Pengaturan', 'route' => 'pengaturan.index', 'active' => 'pengaturan.*', 'icon' => 'M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM19 13a7 7 0 0 0 0-2l2-1-2-4-2 1a7 7 0 0 0-2-1l-.5-2h-5L9 6a7 7 0 0 0-2 1L5 6l-2 4 2 1a7 7 0 0 0 0 2l-2 1 2 4 2-1a7 7 0 0 0 2 1l.5 2h5L15 18a7 7 0 0 0 2-1l2 1 2-4z'],
                ] as $item)
                    <a href="{{ route($item['route']) }}" @class([
                        'mb-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold',
                        'bg-sky-50 text-sky-600' => request()->routeIs($item['active']),
                        'text-slate-500 hover:bg-slate-50 hover:text-sky-600' => ! request()->routeIs($item['active']),
                    ])>
                        <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="{{ $item['icon'] }}"/></svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
            <div class="mx-4 mb-5 rounded-2xl bg-sky-50 p-4">
                <p class="text-xs font-semibold text-sky-700">Apotek Berkah</p>
                <p class="mt-1 text-xs leading-5 text-slate-500">Pengelolaan persediaan obat anak.</p>
            </div>
        </aside>

        <main class="min-w-0">
            <header class="flex h-20 items-center justify-between border-b border-slate-200 bg-white px-5 lg:px-8">
                <div class="flex items-center gap-3 lg:hidden">
                    <span class="grid size-9 place-items-center rounded-xl bg-sky-500 text-white">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v18M3 12h18"/><circle cx="12" cy="12" r="9"/></svg>
                    </span>
                    <span class="font-bold text-slate-900">Apotek Berkah</span>
                </div>
                <div class="hidden lg:block">
                    <p class="text-sm font-semibold text-slate-800">@yield('page_title', 'Dashboard')</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" class="grid size-9 place-items-center rounded-full border border-slate-200 text-slate-500">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>
                    </button>
                </div>
            </header>

            <nav class="no-print flex gap-2 overflow-x-auto border-b border-slate-200 bg-white px-4 py-3 lg:hidden">
                <a href="{{ route('obat-anak.index') }}" class="shrink-0 rounded-lg bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700">Dashboard</a>
                <a href="{{ route('kategori-obat.index') }}" class="shrink-0 rounded-lg px-3 py-2 text-xs font-semibold text-slate-600">Kategori</a>
                <a href="{{ route('obat-anak.create') }}" class="shrink-0 rounded-lg px-3 py-2 text-xs font-semibold text-slate-600">Tambah Obat</a>
                <a href="{{ route('stok-obat.index') }}" class="shrink-0 rounded-lg px-3 py-2 text-xs font-semibold text-slate-600">Stok</a>
                <a href="{{ route('obat-anak.kedaluwarsa') }}" class="shrink-0 rounded-lg px-3 py-2 text-xs font-semibold text-slate-600">Kedaluwarsa</a>
                <a href="{{ route('obat-anak.laporan') }}" class="shrink-0 rounded-lg px-3 py-2 text-xs font-semibold text-slate-600">Laporan</a>
            </nav>

            <div class="page-content p-5 lg:p-8">
                @if(session('success'))
                    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
