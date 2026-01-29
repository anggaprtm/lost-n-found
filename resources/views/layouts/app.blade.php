<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Lost & Found'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        // Biru Tua (#073763) -> Warna Utama (Navbar, Tombol Utama)
                        primary: {
                            DEFAULT: '#073763',
                            light: '#0a4d8c', // Versi agak terang buat hover
                            50: '#f0f9ff',    // Versi pudar buat background badge
                        },
                        // Maroon/Ungu (#741B47) -> Warna Aksen (Alert, Tombol Hapus, Grafik)
                        secondary: {
                            DEFAULT: '#741B47',
                            light: '#9e2b63',
                            50: '#fdf2f8',
                        },
                        // Silver (#c0c0c0) -> Warna Struktur (Border, Divider)
                        neutral: {
                            silver: '#c0c0c0',
                            light: '#f3f4f6', // Background halaman (biar ga kusam)
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Input Style (Gembul & Rapih) */
        .form-input {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            line-height: 1.5rem;
            color: #1f2937;
            background-color: #fff;
            border: 1px solid #d1d5db; /* Default border abu muda */
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }
        .form-input:focus {
            border-color: #073763; /* Fokus Biru Dosen */
            outline: none;
            box-shadow: 0 0 0 3px rgba(7, 55, 99, 0.15);
        }
        .form-input:disabled {
            background-color: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }

        /* Tombol Utama (Biru Dosen) */
        .btn-primary {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 0.625rem 1.25rem;
            background-color: #073763;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .btn-primary:hover {
            background-color: #0a4d8c; /* Biru agak terang saat hover */
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        
        @auth
        <nav x-data="{ open: false }" class="bg-gradient-to-r from-[#741B47] via-[#073763] to-[#04223b] shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="/" class="text-xl font-bold text-white flex items-center gap-2 hover:opacity-90 transition">
                                <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <span class="tracking-tight">Lost & Found</span>
                            </a>
                        </div>
                        
                        <div class="hidden md:ml-10 md:flex md:space-x-4 items-center">
                            @php
                                $navClass = "text-gray-300 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition-colors";
                                $activeClass = "bg-white/20 text-white px-3 py-2 rounded-md text-sm font-medium shadow-sm";
                            @endphp

                            @if(Auth::user()->role == 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? $activeClass : $navClass }}">Dashboard</a>
                                <a href="{{ route('admin.reports.validation') }}" class="{{ request()->routeIs('admin.reports.*') ? $activeClass : $navClass }}">Laporan</a>
                                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? $activeClass : $navClass }}">Users</a>
                                <a href="{{ route('reports.create') }}" class="{{ request()->routeIs('reports.create') ? $activeClass : $navClass }}">Buat Laporan</a>
                                <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.index') ? $activeClass : $navClass }}">Laporan Saya</a>
                                
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="{{ request()->routeIs('admin.categories.*', 'admin.buildings.*', 'admin.rooms.*') ? $activeClass : $navClass }} inline-flex items-center">
                                        <span>Master</span>
                                        <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak class="absolute left-0 mt-2 w-48 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5 py-1 z-50 transform origin-top-left transition-all">
                                        <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Kategori</a>
                                        <a href="{{ route('admin.buildings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Gedung</a>
                                        <a href="{{ route('admin.rooms.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Ruangan</a>
                                    </div>
                                </div>

                            @elseif(Auth::user()->role == 'petugas')
                                <a href="{{ route('petugas.dashboard') }}" class="{{ request()->routeIs('petugas.dashboard') ? $activeClass : $navClass }}">Dashboard</a>
                                <a href="{{ route('petugas.reports') }}" class="{{ request()->routeIs('petugas.reports') ? $activeClass : $navClass }}">Validasi Laporan</a>
                                <a href="{{ route('petugas.claims') }}" class="{{ request()->routeIs('petugas.claims') ? $activeClass : $navClass }}">Validasi Klaim</a>
                                <a href="{{ route('petugas.reports.create') }}" class="{{ request()->routeIs('petugas.reports.create') ? $activeClass : $navClass }}">Buat Laporan</a>
                            @else
                                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? $activeClass : $navClass }}">Dashboard</a>
                                <a href="{{ route('reports.create') }}" class="{{ request()->routeIs('reports.create') ? $activeClass : $navClass }}">Buat Laporan</a>
                                <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.index') ? $activeClass : $navClass }}">Laporan Saya</a>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-gray-200 hover:text-white focus:outline-none transition">
                                    <div class="h-8 w-8 rounded-full bg-white/10 flex items-center justify-center text-xs font-bold border border-white/20">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5 py-1 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-xs text-gray-500">Login sebagai</p>
                                        <p class="text-sm font-bold text-gray-900 capitalize">{{ Auth::user()->role }}</p>
                                    </div>
                                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Log Out</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="-mr-2 flex items-center sm:hidden ml-4">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-white/10 focus:outline-none">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#052c50] border-t border-white/10">
                <div class="pt-2 pb-3 space-y-1 px-2">
                    <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10">Home</a>
                    </div>
                <div class="pt-4 pb-4 border-t border-white/10">
                    <div class="px-4 flex items-center">
                        <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center text-white font-bold border border-white/20">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-400">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-white/10">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        @endauth

        <main class="flex-grow">
            @if (session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="bg-red-50 border-l-4 border-secondary p-4 rounded-r shadow-sm flex items-center">
                        <svg class="h-5 w-5 text-secondary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

<footer class="mt-auto bg-white/70 backdrop-blur-md border-t border-white/30">
    <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            
            <div class="flex items-center gap-2">
                <svg class="w-7 h-7 text-yellow-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="text-lg font-semibold text-slate-800">
                    Lost & Found
                </span>
            </div>

            <p class="text-xs text-slate-600 text-center md:text-right leading-relaxed">
                Lost & Found<br>
                Fakultas Teknologi Maju dan Multidisiplin<br>
                Universitas Airlangga © {{ date('Y') }}
            </p>

        </div>
    </div>
</footer>

    </div>
    
    @stack('scripts')
</body>
</html>