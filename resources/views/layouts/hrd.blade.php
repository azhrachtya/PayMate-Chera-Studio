<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/png" href="{{ asset('images/login-logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
</head>
<body class="bg-stone-50 text-stone-800">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-60 bg-rose-950 text-white flex flex-col h-screen sticky top-0 overflow-y-auto">

            {{-- Logo block — background maroon, teks pink --}}
            @php
                $logoPath = public_path('images/nav-logo.png');
                $logoUrl = file_exists($logoPath) ? asset('images/nav-logo.png') : null;
            @endphp
            <div class="px-6 py-6 border-b border-rose-950 bg-rose-950 flex items-center gap-3">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo Chera Studio" class="w-12 h-12 rounded-md object-contain flex-shrink-0">
                @else
                    <div class="w-12 h-12 rounded-md bg-pink-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-sm">CS</span>
                    </div>
                @endif
                <div>
                    <p class="text-sm tracking-widest uppercase text-pink-250 font-semibold">Chera Studio</p>
                    <p class="font-semibold text-xs leading-tight text-white">Payroll HRD</p>
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'bg-rose-900 text-rose-50' : 'text-white hover:bg-rose-300 hover:text-rose-950' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('employees.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm transition-colors duration-150 {{ request()->routeIs('employees.*') ? 'bg-rose-900 text-rose-50' : 'text-white hover:bg-rose-300 hover:text-rose-950' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                    </svg>
                    Karyawan
                </a>
                <a href="{{ route('attendance.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm transition-colors duration-150 {{ request()->routeIs('attendance.*') ? 'bg-rose-900 text-rose-50' : 'text-white hover:bg-rose-300 hover:text-rose-950' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0V11.25A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-13.5-6h.008v.008h-.008V12.75zm0 3h.008v.008h-.008V15.75zm3-3h.008v.008h-.008V12.75zm0 3h.008v.008h-.008V15.75z" />
                    </svg>
                    Kehadiran
                </a>
                <a href="{{ route('payroll.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm transition-colors duration-150 {{ request()->routeIs('payroll.*') ? 'bg-rose-900 text-rose-50' : 'text-white hover:bg-rose-300 hover:text-rose-950' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Payroll
                </a>
                <a href="{{ route('reports.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm transition-colors duration-150 {{ request()->routeIs('reports.*') ? 'bg-rose-900 text-rose-50' : 'text-white hover:bg-rose-300 hover:text-rose-950' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    Laporan
                </a>
            </nav>

            <div class="px-3 py-4 border-t border-rose-300">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-md text-sm text-white transition-colors duration-150 hover:bg-rose-300 hover:text-rose-950">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H3" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b border-stone-200 px-8 py-4">
                <h1 class="text-lg font-semibold text-stone-900">@yield('title', 'Dashboard')</h1>
            </header>

            <main class="flex-1 px-8 py-6">
                @if (session('info'))
                    <div class="mb-4 rounded-md bg-pink-50 border border-pink-200 text-rose-900 text-sm px-4 py-3">
                        {{ session('info') }}
                    </div>
                @endif

                @if (session('errors_import') && count(session('errors_import')) > 0)
                    <div class="mb-4 rounded-md bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">
                        <p class="font-medium mb-1">Beberapa baris dilewati saat import:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach (session('errors_import') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
</body>
</html>