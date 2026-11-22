<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Apotek Berkah')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8fb] text-slate-800">
    <div class="min-h-screen lg:grid lg:grid-cols-[240px_1fr]">
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
                <a href="{{ route('obat-anak.index') }}" class="flex items-center gap-3 rounded-xl bg-sky-50 px-4 py-3 text-sm font-semibold text-sky-600">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>
            </nav>
            <div class="mx-4 mb-5 rounded-2xl bg-sky-50 p-4">
                <p class="text-xs font-semibold text-sky-700">Bantuan</p>
                <p class="mt-1 text-xs leading-5 text-slate-500">Kelola data obat dengan aman dan tertata.</p>
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
                    <div class="size-9 rounded-full bg-sky-100 text-center text-sm font-bold leading-9 text-sky-700">AB</div>
                </div>
            </header>

            <div class="p-5 lg:p-8">
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
