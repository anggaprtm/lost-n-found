<nav class="bg-gradient-to-r from-[#741B47] via-[#073763] to-[#04223b] shadow-lg fixed w-full top-0 z-50">    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="@if(Auth::user()->role == 'admin') {{ route('admin.dashboard') }} @elseif(Auth::user()->role == 'petugas') {{ route('petugas.dashboard') }} @else {{ route('dashboard') }} @endif" class="text-white text-xl font-bold">
                        Lost & Found
                    </a>
                </div>
                
                <div class="hidden md:ml-10 md:flex md:space-x-8">
                    @if(Auth::user()->role == 'pengguna')
                        {{-- Menu untuk Pengguna --}}
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('reports.public_index') }}" class="nav-link {{ request()->routeIs('reports.public_index') ? 'active' : '' }}">Barang Temuan</a>
                        {{-- PERBAIKI INI: Buat kondisi routeIs lebih spesifik --}}
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs(['reports.index', 'reports.create', 'reports.show', 'reports.edit']) ? 'active' : '' }}">Laporan Saya</a>
                        
                        <a href="{{ route('claims.index') }}" class="nav-link {{ request()->routeIs('claims.*') ? 'active' : '' }}">Claim Saya</a>
                    
                    @elseif(Auth::user()->role == 'petugas')
                        {{-- Menu untuk Petugas --}}
                        <a href="{{ route('petugas.dashboard') }}" class="nav-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('reports.public_index') }}" class="nav-link {{ request()->routeIs('reports.public_index') ? 'active' : '' }}">Barang Temuan</a>
                        <a href="{{ route('petugas.reports') }}" class="nav-link {{ request()->routeIs('petugas.reports') ? 'active' : '' }}">Validasi Laporan</a>
                        <a href="{{ route('petugas.claims') }}" class="nav-link {{ request()->routeIs('petugas.claims') ? 'active' : '' }}">Validasi Klaim</a>

                    @elseif(Auth::user()->role == 'admin')
                        {{-- Menu untuk Admin --}}
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('reports.public_index') }}" class="nav-link {{ request()->routeIs('reports.public_index') ? 'active' : '' }}">Barang Temuan</a>
                        <a href="{{ route('admin.reports.validation') }}" class="nav-link {{ request()->routeIs('admin.reports.validation') ? 'active' : '' }}">Validasi Laporan</a>
                        <a href="{{ route('admin.claims.validation') }}" class="nav-link {{ request()->routeIs('admin.claims.validation') ? 'active' : '' }}">Validasi Klaim</a>
                    @endif
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="relative" id="profile-dropdown">
                    <button id="profile-dropdown-button" class="flex items-center text-white hover:text-yellow-300 transition-colors">
                        <span class="mr-2">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="profile-dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil Saya</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
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
            dropdownButton.addEventListener('click', (event) => { event.stopPropagation(); dropdownMenu.classList.toggle('hidden'); });
            document.addEventListener('click', (event) => { if (!dropdownButton.contains(event.target)) { dropdownMenu.classList.add('hidden'); } });
        }
    });
</script>