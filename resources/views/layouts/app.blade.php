<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Lost & Found'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary: {
                            DEFAULT: '#073763',
                            light: '#0a4d8c',
                            50: '#f0f9ff',
                        },
                        secondary: {
                            DEFAULT: '#741B47',
                            light: '#9e2b63',
                            50: '#fdf2f8',
                        },
                        neutral: {
                            silver: '#c0c0c0',
                            light: '#f3f4f6',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        .form-input {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            line-height: 1.5rem;
            color: #1f2937;
            background-color: #fff;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }
        .form-input:focus {
            border-color: #073763;
            outline: none;
            box-shadow: 0 0 0 3px rgba(7, 55, 99, 0.15);
        }
        .form-input:disabled {
            background-color: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }

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
            background-color: #0a4d8c;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        
        {{-- NAVBAR HANYA MUNCUL JIKA BUKAN HALAMAN LOGIN/REGISTER --}}
        @unless(request()->routeIs('login') || request()->routeIs('register'))
            @auth
            <nav x-data="{ open: false }" class="bg-white shadow-md fixed w-full top-0 z-50 border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <a href="@if(Auth::user()->role == 'admin') {{ route('admin.dashboard') }} @elseif(Auth::user()->role == 'petugas') {{ route('petugas.dashboard') }} @else {{ route('dashboard') }} @endif" 
                                class="flex items-center gap-3 group">
                                    <div class="flex items-center justify-center w-10 h-10 bg-[#741847] rounded-lg 
                                                transition-all duration-300 ease-in-out
                                                group-hover:bg-white group-hover:ring-2 group-hover:ring-[#741847] group-hover:scale-110">
                                        <svg class="w-6 h-6 text-white transition-colors duration-300
                                                    group-hover:text-[#741847]"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[#741847] font-bold text-xl leading-tight 
                                                    transition-colors duration-300
                                                    group-hover:text-[#9b1d5a]">Lost & Found</span>
                                        <span class="text-gray-600 text-xs 
                                                    transition-colors duration-300
                                                    group-hover:text-gray-800">Portal Barang Hilang FTMM</span>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="hidden md:ml-10 md:flex md:space-x-6">
                                @if(Auth::user()->role == 'pengguna')
                                    {{-- Menu untuk Pengguna --}}
                                    <a href="{{ route('dashboard') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('report.public_index') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('report.public_index') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Barang Temuan
                                    </a>
                                    <a href="{{ route('reports.index') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs(['reports.index', 'reports.create', 'reports.show', 'reports.edit']) ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Laporan Saya
                                    </a>
                                    <a href="{{ route('claims.index') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('claims.*') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Klaim Saya
                                    </a>
                                
                                @elseif(Auth::user()->role == 'petugas')
                                    {{-- Menu untuk Petugas --}}
                                    <a href="{{ route('petugas.dashboard') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('petugas.dashboard') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('report.public_index') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('report.public_index') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Barang Temuan
                                    </a>
                                    <a href="{{ route('petugas.reports') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('petugas.reports') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Validasi Laporan
                                    </a>
                                    <a href="{{ route('petugas.claims') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('petugas.claims') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Validasi Klaim
                                    </a>

                                @elseif(Auth::user()->role == 'admin')
                                    {{-- Menu untuk Admin --}}
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('admin.reports.validation') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.reports.validation') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Validasi Laporan
                                    </a>
                                    <a href="{{ route('admin.claims.validation') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.claims.validation') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Validasi Klaim
                                    </a>
                                    <a href="{{ route('reports.create') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('reports.create') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Buat Laporan
                                    </a>
                                    <a href="{{ route('reports.index') }}" 
                                       class="text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('reports.index') ? 'text-[#741B47] bg-purple-50' : '' }}">
                                        Laporan Saya
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="flex items-center gap-2 text-gray-700 hover:text-[#741B47] transition-colors px-3 py-2 rounded-md">
                                    <div class="h-8 w-8 rounded-full bg-[#741B47] flex items-center justify-center text-white text-sm font-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium hidden md:inline">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-1 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-xs text-gray-500">Login sebagai</p>
                                        <p class="text-sm font-bold text-gray-900 capitalize">{{ Auth::user()->role }}</p>
                                    </div>
                                    <a href="{{ route('profile.show') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#741B47] transition-colors">
                                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Profil Saya
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="-mr-2 flex items-center md:hidden ml-4">
                                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-[#741B47] hover:bg-gray-100 focus:outline-none">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-gray-50 border-t border-gray-200">
                    <div class="pt-2 pb-3 space-y-1 px-2">
                        <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-[#741B47] hover:bg-gray-100">Home</a>
                    </div>
                    <div class="pt-4 pb-4 border-t border-gray-200">
                        <div class="px-4 flex items-center">
                            <div class="h-10 w-10 rounded-full bg-[#741B47] flex items-center justify-center text-white font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1 px-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            @else
            <nav class="bg-white absolute top-0 left-0 right-0 z-50">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="flex items-center justify-between h-20">

                        {{-- LOGO KIRI --}}
                        <a href="/" class="flex items-center gap-3 group">
                            <div class="flex items-center justify-center w-10 h-10 bg-[#741847] rounded-lg 
                                        transition-all duration-300 ease-in-out
                                        group-hover:bg-white group-hover:ring-2 group-hover:ring-[#741847] group-hover:scale-110">
                                <svg class="w-6 h-6 text-white transition-colors duration-300
                                            group-hover:text-[#741847]" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[#741847] font-bold text-xl leading-tight 
                                            transition-colors duration-300
                                            group-hover:text-[#9b1d5a]">Lost & Found</span>
                                <span class="text-gray-600 text-xs 
                                            transition-colors duration-300
                                            group-hover:text-gray-800">Portal Barang Hilang FTMM</span>
                            </div>
                        </a>

                        {{-- TOMBOL LOGIN KANAN --}}
                        <!-- <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2
                                px-6 py-2.5 rounded-full
                                bg-[#741B47] text-white
                                font-semibold text-sm
                                shadow-md
                                hover:bg-[#5f163a]
                                hover:shadow-lg
                                transition-all duration-200">

                            <i class="fas fa-sign-in-alt me-1"></i>
                            Login
                        </a> -->


                    </div>
                </div>
            </nav>

            @endauth
        @endunless

        <main class="flex-grow {{ request()->routeIs('login') || request()->routeIs('register') ? '' : 'pt-20' }}">
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

        {{-- FOOTER JUGA HANYA MUNCUL JIKA BUKAN HALAMAN LOGIN/REGISTER --}}
        @unless(request()->routeIs('login') || request()->routeIs('register'))
        <footer class="mt-auto bg-white/70 backdrop-blur-md border-t border-white/30">
            <div class="max-w-7xl mx-auto px-6 py-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    
                    <!-- Logo dengan hover effect -->
                    <div class="flex items-center gap-3 group cursor-pointer">
                        <div class="flex items-center justify-center w-10 h-10 bg-[#741847] rounded-lg 
                                    transition-all duration-300 ease-in-out
                                    group-hover:bg-white group-hover:ring-2 group-hover:ring-[#741847] group-hover:scale-110">
                            <svg class="w-6 h-6 text-white transition-colors duration-300
                                        group-hover:text-[#741847]"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[#741847] font-bold text-lg leading-tight 
                                        transition-colors duration-300
                                        group-hover:text-[#9b1d5a]">Lost & Found</span>
                            <span class="text-gray-600 text-xs 
                                        transition-colors duration-300
                                        group-hover:text-gray-800">Portal Barang Hilang FTMM</span>
                        </div>
                    </div>

                    <!-- Copyright text -->
                    <p class="text-xs text-slate-600 text-center md:text-right leading-relaxed">
                        Lost & Found<br>
                        Fakultas Teknologi Maju dan Multidisiplin<br>
                        USI FTMM © {{ date('Y') }}
                    </p>
                </div>
            </div>
        </footer>
        @endunless

    </div>
    
    @stack('scripts')
</body>
</html>