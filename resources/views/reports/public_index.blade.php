@extends('layouts.app')

@section('title', 'Barang Temuan')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- HEADER SECTION --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                🔍 Barang Temuan
            </h1>
            <p class="text-gray-600">
                Temukan barang yang hilang atau laporkan barang temuan di lingkungan FTMM
            </p>
        </div>

        {{-- INFO BOX --}}
        @if(!auth()->check() || auth()->user()->role == 'pengguna')
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm text-blue-800">
                        <strong>Informasi Klaim:</strong> Barang temuan dapat diklaim dengan 
                        <a href="{{ route('login') }}" class="font-semibold underline hover:text-blue-900">login ke sistem</a>, 
                        atau datang langsung ke <strong>Lt. 10 Ruang Sarpras</strong>. Petugas akan membantu proses klaim Anda.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- SEARCH & FILTER SECTION --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                {{-- FILTER --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

                    {{-- SEARCH --}}
                    <div class="md:col-span-2 relative">
                        <input type="text"
                            id="searchInput"
                            placeholder="Cari nama barang, lokasi, atau deskripsi..."
                            class="w-full rounded-lg border border-gray-300
                                    px-4 py-3 text-sm
                                    focus:ring-2 focus:ring-[#073763]
                                    focus:border-[#073763]">
                    </div>

                    {{-- CATEGORY --}}
                    <div>
                        <select id="categoryInput"
                                class="w-full rounded-lg border border-gray-300
                                    px-4 py-3 text-sm
                                    focus:ring-2 focus:ring-[#073763]
                                    focus:border-[#073763]">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>


                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div id="reportGrid"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                        @include('reports._cards', [
                            'reports' => $reports,
                            'highlight' => request('search')
                        ])

                    </div>

                    <template id="skeletonTemplate">
                        <div class="animate-pulse bg-white rounded-xl border p-4 space-y-3">
                            <div class="h-40 bg-gray-200 rounded"></div>
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-3 bg-gray-200 rounded w-full"></div>
                            <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                        </div>
                    </template>


                </div>


            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput   = document.getElementById('searchInput');
    const categoryInput = document.getElementById('categoryInput');
    const reportGrid    = document.getElementById('reportGrid');

    let debounceTimer = null;

    function showSkeleton() {
        const template = document.getElementById('skeletonTemplate').innerHTML;
        reportGrid.innerHTML = template.repeat(8);
    }

    function fetchResults() {
        const search   = searchInput.value;
        const category = categoryInput.value;

        showSkeleton();

        fetch(`{{ route('temuan.search') }}?search=${encodeURIComponent(search)}&category=${category}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            reportGrid.innerHTML = html;
        })
        .catch(() => {
            reportGrid.innerHTML = `<p class="col-span-full text-center text-sm text-gray-500">Gagal memuat data.</p>`;
        });
    }


    function debounceFetch() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchResults, 400); // ⏱️ debounce 400ms
    }

    searchInput.addEventListener('input', debounceFetch);
    categoryInput.addEventListener('change', fetchResults);
});
</script>

