@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Petugas</h1>
            <p class="text-gray-500 mt-1">Pusat Validasi & Manajemen Barang Temuan</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-2 text-sm text-gray-500 bg-white px-3 py-1 rounded-full shadow-sm border border-gray-200">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            <span>Gudang Aktif</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-secondary hover:shadow-md transition duration-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Validasi Pending</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['pending_tasks'] }}</p>
                    <p class="text-xs text-red-500 mt-1 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Perlu segera diproses
                    </p>
                </div>
                <div class="p-2 bg-red-50 rounded-lg text-secondary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-primary hover:shadow-md transition duration-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Temuan Bulan Ini</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['items_this_month'] }}</p>
                    <p class="text-xs text-blue-600 mt-1">Barang masuk gudang</p>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-green-500 hover:shadow-md transition duration-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Berhasil Dikembalikan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['my_performance'] }}</p>
                    <p class="text-xs text-green-600 mt-1">Kontribusi Anda</p>
                </div>
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Status Barang</h2>
            <p class="text-xs text-gray-500 mb-4">Komposisi stok saat ini</p>
            <div class="h-64">
                <canvas id="warehouseChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">📈 Arus Barang Masuk</h2>
                    <p class="text-xs text-gray-500">Tren penemuan barang periode ini</p>
                </div>
                <select id="trendFilter" class="form-input w-auto py-1 px-3 text-xs">
                    <option value="daily" selected>Harian</option>
                    <option value="weekly">Mingguan</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Kategori Barang</h2>
            <div class="h-64">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Daftar Laporan Operasional</h2>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('petugas.dashboard') }}" class="mb-4 p-4 bg-gray-50 rounded-lg">
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
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Validasi</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Tervalidasi</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
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
                                @php
                                    $typeMap = [
                                        'lost' => [
                                            'label' => 'Laporan Kehilangan',
                                            'class' => 'bg-red-100 text-red-800',
                                        ],
                                        'found' => [
                                            'label' => 'Temuan',
                                            'class' => 'bg-green-100 text-green-800',
                                        ],
                                    ];
                                @endphp

                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $typeMap[$report->type]['class'] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $typeMap[$report->type]['label'] ?? ucfirst($report->type) }}
                                </span>

                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusMap = [
                                        'pending' => ['label' => 'Menunggu Validasi', 'class' => 'bg-yellow-100 text-yellow-800'],
                                        'approved' => ['label' => 'Tervalidasi', 'class' => 'bg-blue-100 text-blue-800'],
                                        'rejected' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-800'],
                                        'returned' => ['label' => 'Dikembalikan', 'class' => 'bg-purple-100 text-purple-800'],
                                    ];
                                @endphp

                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $statusMap[$report->status]['class'] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusMap[$report->status]['label'] ?? ucfirst($report->status) }}
                                </span>

                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $report->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right text-sm font-medium">
                                <a href="{{ route('petugas.reports.show', $report) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
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
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#6B7280';

    // Warna Branding
    const colorPrimary = '#073763';
    const colorSecondary = '#741B47';

    // --- 1. CHART STATUS (Doughnut) ---
    const whCtx = document.getElementById('warehouseChart').getContext('2d');
    new Chart(whCtx, {
        type: 'doughnut',
        data: {
            labels: @json($warehouseComposition->pluck('label')),
            datasets: [{
                data: @json($warehouseComposition->pluck('total')),
                // Warna: Pending (Kuning), Approved (Hijau/Biru), Returned (Abu)
                backgroundColor: ['#F59E0B', colorPrimary, '#9CA3AF', colorSecondary], 
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) label += ': ';
                            let value = context.raw;
                            let total = context.chart._metasets[context.datasetIndex].total;
                            let percentage = Math.round((value / total) * 100) + '%';
                            return label + value + ' (' + percentage + ')';
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });

    // --- 2. CHART TREN PENEMUAN (Line - AJAX) ---
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    let trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Barang Masuk',
                data: [],
                borderColor: colorPrimary, // Biru Dosen
                backgroundColor: 'rgba(7, 55, 99, 0.05)', // Biru Pudar
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: colorPrimary
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f3f4f6' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });

    function fetchWarehouseTrend(filter) {
        fetch(`{{ route('petugas.dashboard.trend') }}?filter=${filter}`)
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(data => {
                trendChart.data.labels = data.labels;
                trendChart.data.datasets[0].data = data.data;
                trendChart.update();
            })
            .catch(error => {
                console.error('Chart Error:', error);
                trendChart.data.labels = ['No Data'];
                trendChart.data.datasets[0].data = [0];
                trendChart.update();
            });
    }

    // Load Default
    fetchWarehouseTrend('daily');
    document.getElementById('trendFilter').addEventListener('change', function() {
        fetchWarehouseTrend(this.value);
    });

    // --- 3. CHART KATEGORI (Bar) ---
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'bar',
        data: {
            labels: @json($categoryDistribution->pluck('label')),
            datasets: [{
                label: 'Jumlah Item',
                data: @json($categoryDistribution->pluck('total')),
                backgroundColor: colorPrimary, // Biru Dosen
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            indexAxis: 'y',
            scales: {
                x: { grid: { borderDash: [4, 4], color: '#f3f4f6' } },
                y: { grid: { display: false } }
            }
        }
    });
</script>
@endpush