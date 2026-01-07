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
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-900">🔔 Tugas Menunggu</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('petugas.reports') }}" class="text-xs bg-blue-50 text-primary px-3 py-1 rounded-lg hover:bg-blue-100 font-medium transition">Laporan</a>
                    <a href="{{ route('petugas.claims') }}" class="text-xs bg-purple-50 text-purple-700 px-3 py-1 rounded-lg hover:bg-purple-100 font-medium transition">Klaim</a>
                </div>
            </div>

            <div class="space-y-3">
                @foreach($recentReports as $report)
                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:border-red-300 hover:shadow-sm transition group">
                    <div class="flex items-center space-x-3">
                        <div class="bg-red-50 p-2 rounded-lg text-secondary group-hover:bg-secondary group-hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Laporan Baru: {{ $report->item_name }}</p>
                            <p class="text-xs text-gray-500">Oleh {{ $report->user->name }} • {{ $report->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('petugas.reports') }}" class="text-xs font-semibold text-secondary bg-red-50 px-3 py-1.5 rounded-md hover:bg-secondary hover:text-white transition">
                        Validasi
                    </a>
                </div>
                @endforeach

                @foreach($recentClaims as $claim)
                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:border-purple-300 hover:shadow-sm transition group">
                    <div class="flex items-center space-x-3">
                        <div class="bg-purple-50 p-2 rounded-lg text-purple-700 group-hover:bg-purple-600 group-hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Klaim Masuk: {{ $claim->report->item_name }}</p>
                            <p class="text-xs text-gray-500">Oleh {{ $claim->user->name }} • {{ $claim->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('petugas.claims') }}" class="text-xs font-semibold text-purple-700 bg-purple-50 px-3 py-1.5 rounded-md hover:bg-purple-600 hover:text-white transition">
                        Proses
                    </a>
                </div>
                @endforeach

                @if($recentReports->isEmpty() && $recentClaims->isEmpty())
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-50 mb-3">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-sm text-gray-500">Tidak ada antrean validasi saat ini. Aman! 👍</p>
                </div>
                @endif
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