<nav class="bg-white shadow-md fixed w-full top-0 z-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="@if(Auth::user()->role == 'admin') {{ route('admin.dashboard') }} @elseif(Auth::user()->role == 'petugas') {{ route('petugas.dashboard') }} @else {{ route('dashboard') }} @endif" 
                       class="flex items-center gap-2 text-[#741B47] text-xl font-bold hover:text-[#5f163a] transition-colors">
                        <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Lost & Found
                    </a>
                </div>
                
                <div class="hidden md:ml-10 md:flex md:space-x-6">
                    @if(Auth::user()->role == 'pengguna')
                        {{-- Menu untuk Pengguna --}}
                        <a href="{{ route('dashboard') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('landing') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('welcome') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Barang Temuan
                        </a>
                        <a href="{{ route('reports.index') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs(['reports.index', 'reports.create', 'reports.show', 'reports.edit']) ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Laporan Saya
                        </a>
                        <a href="{{ route('claims.index') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('claims.*') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Klaim Saya
                        </a>
                    
                    @elseif(Auth::user()->role == 'petugas')
                        {{-- Menu untuk Petugas --}}
                        <a href="{{ route('petugas.dashboard') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('petugas.dashboard') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('report.public_index') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('report.public_index') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Barang Temuan
                        </a>
                        <a href="{{ route('petugas.reports') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('petugas.reports') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Validasi Laporan
                        </a>
                        <a href="{{ route('petugas.claims') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('petugas.claims') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Validasi Klaim
                        </a>

                    @elseif(Auth::user()->role == 'admin')
                        {{-- Menu untuk Admin --}}
                        <a href="{{ route('admin.dashboard') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('report.public_index') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('report.public_index') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Barang Temuan
                        </a>
                        <a href="{{ route('admin.reports.validation') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.reports.validation') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Validasi Laporan
                        </a>
                        <a href="{{ route('admin.claims.validation') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.claims.validation') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Validasi Klaim
                        </a>
                        <a href="{{ route('reports.create') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('reports.create') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Buat Laporan
                        </a>
                        <a href="{{ route('reports.index') }}" 
                           class="nav-link text-gray-700 hover:text-[#741B47] px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('reports.index') ? 'text-[#741B47] bg-purple-50' : '' }}">
                            Laporan Saya
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="relative" id="profile-dropdown">
                    <button id="profile-dropdown-button" 
                            class="flex items-center gap-2 text-gray-700 hover:text-[#741B47] transition-colors px-3 py-2 rounded-md">
                        <div class="h-8 w-8 rounded-full bg-[#741B47] flex items-center justify-center text-white text-sm font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="font-medium">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="profile-dropdown-menu" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-1 hidden">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs text-gray-500">Login sebagai</p>
                            <p class="text-sm font-bold text-gray-900 capitalize">{{ auth()->user()->role }}</p>
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
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownButton = document.getElementById('profile-dropdown-button');
        const dropdownMenu = document.getElementById('profile-dropdown-menu');
        if (dropdownButton) {
            dropdownButton.addEventListener('click', (event) => { 
                event.stopPropagation(); 
                dropdownMenu.classList.toggle('hidden'); 
            });
            document.addEventListener('click', (event) => { 
                if (!dropdownButton.contains(event.target)) { 
                    dropdownMenu.classList.add('hidden'); 
                } 
            });
        }
    });
</script>