@extends('layouts.app')

@section('title', 'FTMM Lost & Found')

@section('content')

<section class="relative min-h-[90vh] overflow-visible">

    {{-- BACKGROUND IMAGE --}}
    <div class="absolute inset-0">
        <img src="/images/GKB.jpg"
             alt="FTMM"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b
                    from-[#073763]/90
                    via-[#073763]/70
                    to-[#741B47]/80">
        </div>
    </div>

    {{-- HERO CONTENT --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6
                pt-40 pb-56 text-center text-white">

        <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight">
            FTMM Lost & Found
        </h1>

        <p class="mt-4 text-base md:text-lg text-gray-200 max-w-2xl mx-auto">
            Temukan kembali barang yang hilang atau laporkan barang temuan
            di lingkungan Fakultas Teknologi Maju dan Multidisiplin.
        </p>

        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">

            {{-- GLASS BUTTON PRIMARY --}}
            <a href="#lost-found"
            class="inline-flex items-center justify-center
                    px-8 py-3 rounded-full
                    bg-white/20 backdrop-blur-md
                    border border-white/30
                    text-white font-semibold
                    shadow-lg
                    hover:bg-white/30
                    hover:-translate-y-0.5
                    hover:shadow-xl
                    transition-all duration-300">
                Lihat Barang
            </a>

            {{-- GLASS BUTTON OUTLINE --}}
            <a href="{{ auth()->check() ? route('reports.create') : route('login') }}"
            class="inline-flex items-center justify-center
                    px-8 py-3 rounded-full
                    bg-white/10 backdrop-blur-md
                    border border-white/40
                    text-white font-semibold
                    shadow-md
                    hover:bg-white/25
                    hover:text-white
                    hover:-translate-y-0.5
                    transition-all duration-300">
                Buat Laporan
            </a>

        </div>

    </div>

    {{-- FLOATING PANEL --}}
    <div id="lost-found"
         class="relative z-20 -mt-32">

        <div class="max-w-7xl mx-auto px-6">

            {{-- PANEL CONTAINER --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">

                {{-- INFO KLAIM --}}
                <div class="mb-6 rounded-xl
                            bg-blue-50 border border-blue-200
                            px-5 py-4 text-sm text-blue-800">
                    <strong>Informasi Klaim:</strong>
                    Barang temuan dapat diklaim dengan
                    <strong>login ke sistem</strong>,
                    atau datang langsung ke
                    <strong>Lt. 10 Ruang Sarpras</strong>.
                    Petugas akan membantu proses klaim Anda.
                </div>

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

    <section class="bg-white py-16"></section>


</section>

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

