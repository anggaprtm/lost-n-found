@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard (BI)</h1>
            <p class="text-gray-500 mt-1">Business Intelligence & Operational Monitoring</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-2 text-sm text-gray-500 bg-white px-3 py-1 rounded-full shadow-sm border border-gray-200">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            <span>System Live</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-secondary hover:shadow-md transition duration-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Kehilangan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_reports'] }}</p>
                </div>
                <div class="p-2 bg-red-50 rounded-lg text-secondary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-primary hover:shadow-md transition duration-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Ditemukan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_found'] }}</p>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm cursor-pointer hover:border-secondary transition-colors" onclick="window.location='{{ route('admin.reports.validation') }}'">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Menunggu Validasi</p>
                    <p class="text-3xl font-bold text-secondary mt-1">{{ $stats['pending_validations'] }}</p>
                </div>
                <div class="animate-pulse">
                    <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-2">🔥 Lokasi Rawan</h2>
            <p class="text-xs text-gray-500 mb-6">Klik bar untuk detail ruangan</p>
            <div class="h-64">
                <canvas id="locationChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-gray-900">📈 Tren Laporan</h2>
                <select id="trendFilter" class="form-input w-auto py-1 px-3 text-xs">
                    <option value="daily">Harian</option>
                    <option value="weekly">Mingguan</option>
                    <option value="monthly" selected>Bulanan</option>
                    <option value="yearly">Tahunan</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-4">📅 Distribusi Hari</h2>
            <div class="h-64">
                <canvas id="dayChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Proporsi Kategori</h2>
            <p class="text-xs text-gray-500 mb-4">Berdasarkan jenis barang</p>
            <div class="h-64">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Daftar Laporan Operasional</h2>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4 p-4 bg-gray-50 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipe Laporan</label>
                    <select name="type" id="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Semua Tipe</option>
                        <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>Temuan</option>
                        <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>Kehilangan</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status Laporan</label>
                    <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                </div>
                <div class="self-end">
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Filter</button>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelapor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="relative px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ \Illuminate\Support\Str::limit($report->item_name, 30) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $report->user->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $report->type == 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($report->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @switch($report->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('approved') bg-blue-100 text-blue-800 @break
                                    @case('rejected') bg-red-100 text-red-800 @break
                                    @case('returned') bg-purple-100 text-purple-800 @break
                                @endswitch
                            ">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $report->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right text-sm font-medium">
                            <a href="{{ route('admin.reports.show', $report) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            Tidak ada laporan yang cocok dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="mt-4">
            {{ $reports->links() }}
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
        <h2 class="text-lg font-bold text-gray-900 mb-4">⚙️ Akses Cepat Master Data</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-white hover:shadow-md hover:border-gray-200 border border-transparent transition-all group">
                <div class="bg-blue-100 rounded-lg p-3 mr-4 group-hover:bg-primary group-hover:text-white transition-colors text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Pengguna</p>
                    <p class="text-xs text-gray-500">Kelola akun</p>
                </div>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-white hover:shadow-md hover:border-gray-200 border border-transparent transition-all group">
                <div class="bg-green-100 rounded-lg p-3 mr-4 group-hover:bg-green-600 group-hover:text-white transition-colors text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Kategori</p>
                    <p class="text-xs text-gray-500">Jenis barang</p>
                </div>
            </a>
            <a href="{{ route('admin.buildings.index') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-white hover:shadow-md hover:border-gray-200 border border-transparent transition-all group">
                <div class="bg-purple-100 rounded-lg p-3 mr-4 group-hover:bg-secondary group-hover:text-white transition-colors text-secondary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Gedung</p>
                    <p class="text-xs text-gray-500">Lokasi kampus</p>
                </div>
            </a>
            <a href="{{ route('admin.rooms.index') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-white hover:shadow-md hover:border-gray-200 border border-transparent transition-all group">
                <div class="bg-yellow-100 rounded-lg p-3 mr-4 group-hover:bg-yellow-500 group-hover:text-white transition-colors text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Ruangan</p>
                    <p class="text-xs text-gray-500">Detail lokasi</p>
                </div>
            </a>
        </div>
    </div>
</div>

<div id="roomModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="modalOverlay"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modalTitle">Detail Kehilangan</h3>
                        <div class="mt-4 h-64 w-full">
                            <canvas id="roomDetailChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="closeModalBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#6B7280';
    
    // Warna Branding
    const colorPrimary = '#073763';
    const colorSecondary = '#741B47';

    // --- 1. CHART LOKASI (BAR) ---
    const locCtx = document.getElementById('locationChart').getContext('2d');
    const locationChart = new Chart(locCtx, {
        type: 'bar',
        data: {
            labels: @json($topLocations->pluck('name')),
            datasets: [{
                label: 'Jumlah Kehilangan',
                data: @json($topLocations->pluck('total')),
                backgroundColor: colorSecondary, 
                borderRadius: 6,
                barPercentage: 0.6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { grid: { display: false } }, y: { grid: { display: false } } },
            onClick: (e) => {
                const points = locationChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
                if (points.length) {
                    const firstPoint = points[0];
                    const label = locationChart.data.labels[firstPoint.index];
                    openRoomDetail(label);
                }
            }
        }
    });

    // --- 2. CHART TREN (LINE) ---
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    let trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Kehilangan (Lost)',
                    data: [],
                    borderColor: colorSecondary,
                    backgroundColor: 'rgba(116, 27, 71, 0.05)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3
                },
                {
                    label: 'Ditemukan (Found)',
                    data: [],
                    borderColor: colorPrimary,
                    backgroundColor: 'rgba(7, 55, 99, 0.05)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f3f4f6' } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { position: 'top', align: 'end', labels: { boxWidth: 10, usePointStyle: true } } }
        }
    });

    function fetchTrendData(filterType) {
        fetch(`{{ route('admin.dashboard.trend') }}?filter=${filterType}`)
            .then(response => response.json())
            .then(data => {
                trendChart.data.labels = data.labels;
                trendChart.data.datasets[0].data = data.lost;
                trendChart.data.datasets[1].data = data.found;
                trendChart.update();
            });
    }
    fetchTrendData('monthly');
    document.getElementById('trendFilter').addEventListener('change', function() {
        fetchTrendData(this.value);
    });

    // --- 3. CHART KATEGORI (DOUGHNUT) ---
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: @json($reportsByCategory->pluck('name')),
            datasets: [{
                data: @json($reportsByCategory->pluck('total')),
                backgroundColor: [colorPrimary, colorSecondary, '#F59E0B', '#10B981', '#6366F1'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right', labels: { boxWidth: 12, usePointStyle: true } } },
            cutout: '70%'
        }
    });

    // --- 4. CHART HARI (BAR SIMPLE) ---
    const dayCtx = document.getElementById('dayChart').getContext('2d');
    new Chart(dayCtx, {
        type: 'bar',
        data: {
            labels: @json($dayAnalysis->pluck('day')),
            datasets: [{
                label: 'Kejadian',
                data: @json($dayAnalysis->pluck('total')),
                backgroundColor: '#F59E0B',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false },
                x: { grid: { display: false } }
            }
        }
    });

    // --- 5. MODAL LOGIC (DIPERBAIKI) ---
    let roomChart = null;
    
    // Fungsi Tampilkan Modal
    function showModal() {
        const modal = document.getElementById('roomModal');
        modal.classList.remove('hidden');
        modal.style.display = 'block'; // Pastikan display block
    }

    // Fungsi Sembunyikan Modal
    function hideModal() {
        const modal = document.getElementById('roomModal');
        modal.classList.add('hidden');
        modal.style.display = 'none'; // Pastikan display none
    }

    document.getElementById('closeModalBtn').addEventListener('click', hideModal);
    document.getElementById('modalOverlay').addEventListener('click', hideModal);
    
    // Tutup dengan tombol ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            hideModal();
        }
    });

    window.openRoomDetail = function(gedungName) {
        showModal();
        document.getElementById('modalTitle').innerText = 'Detail Ruangan di ' + gedungName;

        fetch(`{{ route('admin.dashboard.location-details') }}?gedung=${encodeURIComponent(gedungName)}`)
            .then(res => res.json())
            .then(data => {
                const labels = data.map(d => d.nama_ruangan);
                const values = data.map(d => d.total);
                const ctx = document.getElementById('roomDetailChart').getContext('2d');
                
                if (roomChart) roomChart.destroy();

                roomChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah',
                            data: values,
                            backgroundColor: colorPrimary,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } }
                    }
                });
            });
    }
</script>
@endsection