<section id="temuan" class="bg-gray-50 py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight">Barang Temuan Terbaru</h2>
            <p class="mt-4 text-lg text-gray-600">Lihat barang-barang yang telah ditemukan dan diverifikasi oleh petugas kami.</p>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm p-4 mb-8 sticky top-0 z-20">
            <form method="GET" action="{{ url()->current() }}#temuan" class="flex flex-col md:flex-row gap-4 items-center">
                <div class="w-full md:flex-1">
                    <label for="search" class="sr-only">Cari barang</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama barang..." class="form-input w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="w-full md:w-auto">
                    <label for="category" class="sr-only">Kategori</label>
                    <select name="category" id="category" class="form-select w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-auto">
                    <button type="submit" class="btn-primary w-full md:w-auto">Cari</button>
                </div>
            </form>
        </div>

        {{-- Card Grid --}}
        @if($reports->count() > 0)
            {{-- Ubah ke lg:grid-cols-4 agar card lebih kecil dan muat banyak --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($reports as $report)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden group hover:shadow-xl transition-all duration-300 border border-gray-100">
                        <a href="{{ route('reports.show', $report) }}" class="block relative">
                            {{-- Container Gambar dengan Tinggi Tetap (Fixed Height) --}}
                            <div class="h-48 w-full bg-gray-100 overflow-hidden">
                                @if($report->photo)
                                    {{-- object-cover wajib ada agar gambar tidak gepeng --}}
                                    <img src="{{ Storage::url($report->photo) }}" 
                                        alt="{{ $report->item_name }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                        onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=Image+Not+Found'">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                
                                {{-- Opsional: Badge Tanggal di atas gambar agar hemat tempat --}}
                                <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded text-xs font-semibold text-gray-600 shadow-sm">
                                    {{ $report->event_date->format('d M Y') }}
                                </div>
                            </div>
                        </a>

                        <div class="p-4"> {{-- Padding dikurangi jadi p-4 --}}
                            {{-- Judul dengan line-clamp-1 (maks 1 baris) --}}
                            <h3 class="text-lg font-bold text-gray-900 line-clamp-1 group-hover:text-primary transition-colors" title="{{ $report->item_name }}">
                                <a href="{{ route('reports.show', $report) }}">{{ $report->item_name }}</a>
                            </h3>

                            {{-- Deskripsi dengan line-clamp-2 (maks 2 baris) --}}
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2 h-10 leading-tight">
                                {{ $report->description }}
                            </p>

                            <div class="mt-4 flex items-center justify-between border-t pt-3 border-gray-100">
                                <span class="text-xs text-gray-400">ID: #{{ $report->id }}</span>
                                <a href="{{ route('reports.show', $report) }}" class="text-sm font-medium text-primary hover:text-blue-700 transition-colors flex items-center gap-1">
                                    Lihat
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State (Tetap sama) --}}
            <div class="text-center py-12 px-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Tidak Ada Barang Ditemukan</h3>
                <p class="text-sm text-gray-500 mt-1">Coba kata kunci atau filter lain.</p>
            </div>
        @endif

        {{-- Pagination --}}
        @if ($reports->hasPages())
            <div class="mt-8">
                {{ $reports->withQueryString()->links() }}
            </div>
        @endif
    </div>
</section>