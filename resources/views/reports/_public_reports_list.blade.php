<section id="temuan" class="min-h-screen bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-6 text-center">Daftar Laporan Terverifikasi</h2>

        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            {{-- Tambahkan ID #temuan di action agar saat submit scroll otomatis ke sini --}}
            <form method="GET" action="{{ url()->current() }}#temuan" class="flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..." class="form-input w-full">
                </div>
                <div>
                    <select name="type" class="form-select w-full rounded-md border-gray-300">
                        <option value="">Semua Tipe</option>
                        <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>Barang Temuan</option>
                        <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>Barang Hilang</option>
                    </select>
                </div>
                <div>
                    <select name="category" class="form-select w-full rounded-md border-gray-300">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="building" class="form-select w-full rounded-md border-gray-300">
                        <option value="">Semua Gedung</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building') == $building->id ? 'selected' : '' }}>{{ $building->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn-primary">Cari</button>
                <a href="{{ url()->current() }}#temuan" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Reset</a>
                
                {{-- JANGAN TARUH TOMBOL KLAIM DI SINI --}}
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reports as $report)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if($report->photo)
                                                <img class="h-12 w-12 rounded-lg object-cover" src="{{ Storage::url($report->photo) }}" alt="Foto {{ $report->item_name }}" onerror="this.onerror=null; this.src='https://placehold.co/100x100?text=No+Image'">
                                            @else
                                                <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $report->item_name }}</div>
                                            <div class="text-sm text-gray-500">Oleh: {{ optional($report->user)->name ?? 'Anonim' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $report->type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $report->type === 'lost' ? 'Barang Hilang' : 'Barang Temuan' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ optional($report->category)->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ optional($report->room)->name ?? 'Lokasi tidak spesifik' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $report->event_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @guest
                                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-900">Login untuk Klaim</a>
                                    @endguest

                                    @auth
                                        {{-- Logika Tombol Aksi --}}
                                        @if(in_array(Auth::user()->role, ['petugas', 'admin']))
                                            {{-- Admin/Petugas lihat detail --}}
                                            <a href="{{ route('petugas.reports.show', $report) }}" class="text-blue-600 hover:text-blue-900 font-medium">Detail</a>
                                        
                                        @elseif(Auth::user()->role === 'pengguna')
                                            {{-- User biasa --}}
                                            <a href="{{ route('reports.show', $report) }}" class="text-gray-600 hover:text-gray-900 mr-2">Detail</a>
                                            
                                            {{-- Tombol Klaim (Hanya untuk Temuan & Bukan punya sendiri) --}}
                                            @if($report->type === 'found' && $report->user_id !== Auth::id())
                                                <button type="button" 
                                                        class="text-indigo-600 hover:text-indigo-900 font-bold claim-button" 
                                                        data-report-id="{{ $report->id }}" 
                                                        data-report-name="{{ $report->item_name }}">
                                                    Klaim
                                                </button>
                                            @endif
                                        @endif
                                    @endauth
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 px-6">
                                    <p class="text-gray-500">Tidak ada laporan terverifikasi yang cocok dengan filter Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             @if ($reports->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $reports->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</section>

<div id="claimModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all scale-100">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 id="modalReportName" class="text-xl font-bold text-gray-800">Klaim Barang</h3>
            <button id="closeModalButton" class="text-gray-500 hover:text-gray-800 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <form id="claimForm" method="POST" action=""> 
                @csrf
                <div class="mb-4">
                    <label for="modalDescription" class="block text-sm font-semibold text-gray-800 mb-2">
                        Bukti Kepemilikan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="modalDescription" rows="5" 
                              class="w-full px-3 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                              placeholder="Jelaskan ciri-ciri khusus, nomor seri, atau detail lain yang membuktikan barang ini milik Anda." 
                              required></textarea>
                    <p class="mt-2 text-xs text-gray-500">Jelaskan sedetail mungkin agar klaim mudah diverifikasi.</p>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Ajukan Klaim</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT MODAL (Pastikan jalan) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const claimModal = document.getElementById('claimModal');
    const closeModalButton = document.getElementById('closeModalButton');
    const claimForm = document.getElementById('claimForm');
    const modalReportName = document.getElementById('modalReportName');
    const modalDescription = document.getElementById('modalDescription');
    
    // Gunakan event delegation untuk menangani tombol klaim (bahkan setelah refresh ajax/halaman)
    document.body.addEventListener('click', function(event) {
        if (event.target.classList.contains('claim-button')) {
            const button = event.target;
            const reportId = button.dataset.reportId;
            const reportName = button.dataset.reportName;
            
            // Set Modal Info
            modalReportName.textContent = 'Klaim Barang: ' + reportName;
            claimForm.action = '/reports/' + reportId + '/claims'; // Pastikan route ini benar di web.php
            modalDescription.value = ''; // Reset text area

            // Buka Modal
            claimModal.classList.remove('hidden');
        }
    });

    // Tutup Modal
    function closeModal() {
        claimModal.classList.add('hidden');
    }

    closeModalButton.addEventListener('click', closeModal);
    
    // Tutup jika klik di luar modal
    claimModal.addEventListener('click', function(event) {
        if (event.target === claimModal) {
            closeModal();
        }
    });
});
</script>