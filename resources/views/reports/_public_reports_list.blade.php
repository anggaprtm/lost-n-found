<section id="temuan" class="bg-gray-50">

    {{-- SECTION HEADER / MINI HERO --}}
    <div class="relative bg-cover bg-center"
         style="background-image: url('/images/GKB.jpg');">
        <div class="absolute inset-0 bg-gradient-to-b
                    from-[#073763]/90 to-[#741B47]/80"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
                    py-16 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight">
                Barang Hilang & Ditemukan
            </h2>
            <p class="mt-4 text-base md:text-lg text-white/90 max-w-3xl mx-auto">
                Daftar laporan barang
                <span class="font-semibold text-red-300">kehilangan</span>
                dan
                <span class="font-semibold text-green-300">temuan</span>
                yang telah diverifikasi oleh petugas FTMM.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- BACK --}}
        <div class="mb-6">
            <a href="/"
               class="inline-flex items-center gap-2
                      px-4 py-2 rounded-lg
                      border border-gray-300
                      text-sm font-medium text-gray-700
                      bg-white hover:bg-gray-50 transition">
                <span class="text-lg">&larr;</span>
                Kembali ke Beranda
            </a>
        </div>

        {{-- INFO KLAIM --}}
        <div class="mb-8 rounded-xl border border-blue-200
                    bg-blue-50 px-5 py-4 text-sm text-blue-800">
            <strong>Informasi Klaim:</strong>
            Barang temuan dapat diklaim dengan
            <strong>login ke sistem</strong>,
            atau datang langsung ke
            <strong>Lt. 10 Ruang Sarpras</strong>. Petugas akan membantu proses klaim Anda.
        </div>

        {{-- FILTER BAR --}}
        <div class="bg-white/90 backdrop-blur
                    rounded-xl shadow-md
                    p-4 md:p-5 mb-10
                    sticky top-0 z-20
                    border border-gray-100">

            <form method="GET"
                  action="{{ url()->current() }}#temuan"
                  class="flex flex-col md:flex-row
                         gap-3 md:gap-4
                         items-stretch md:items-center">

                {{-- SEARCH --}}
                <div class="relative w-full md:flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3
                                 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0
                                     7 7 0 0114 0z" />
                        </svg>
                    </span>

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama barang, lokasi, atau deskripsi..."
                           class="w-full pl-11 pr-4 py-3
                                  rounded-lg border border-gray-300
                                  text-sm shadow-sm
                                  focus:ring-2 focus:ring-primary
                                  focus:border-primary">
                </div>

                {{-- CATEGORY --}}
                <div class="relative w-full md:w-56">
                    <select name="category"
                            class="w-full appearance-none px-4 py-3 pr-10
                                   rounded-lg border border-gray-300
                                   text-sm bg-white shadow-sm
                                   focus:ring-2 focus:ring-primary
                                   focus:border-primary">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <span class="absolute inset-y-0 right-0 pr-3
                                 flex items-center text-gray-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>

                {{-- SUBMIT --}}
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2
                               px-6 py-3 rounded-lg
                               bg-primary text-white
                               text-sm font-semibold
                               shadow-md hover:shadow-lg
                               hover:bg-primary/90 transition">
                    Cari
                </button>
            </form>
        </div>

        {{-- LOADING SKELETON --}}
        <template id="skeleton-template">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @for ($i = 0; $i < 8; $i++)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-pulse">
                    <div class="h-48 bg-gray-200"></div>
                    <div class="p-4 space-y-3">
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-3 bg-gray-200 rounded w-full"></div>
                        <div class="h-3 bg-gray-200 rounded w-5/6"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/3 mt-4"></div>
                    </div>
                </div>
                @endfor
            </div>
        </template>


        <div id="report-grid">
            @include('reports._cards', ['reports' => $reports])
        </div>

        {{-- PAGINATION --}}
        @if ($reports->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $reports->withQueryString()->links() }}
            </div>
        @endif
        </div>
    </div>
</section>

<script>
let delay;
const searchInput = document.querySelector('input[name="search"]');
const categorySelect = document.querySelector('select[name="category"]');
const grid = document.getElementById('report-grid');
const skeleton = document.getElementById('skeleton-template').innerHTML;

function liveSearch() {
    clearTimeout(delay);

    delay = setTimeout(() => {
        // tampilkan skeleton
        grid.innerHTML = skeleton;

        const params = new URLSearchParams({
            search: searchInput.value,
            category: categorySelect.value
        });

        fetch(`{{ route('temuan.search') }}?${params}`)
            .then(res => res.text())
            .then(html => {
                grid.innerHTML = html;
            });
    }, 400);
}

searchInput.addEventListener('input', liveSearch);
categorySelect.addEventListener('change', liveSearch);
</script>

