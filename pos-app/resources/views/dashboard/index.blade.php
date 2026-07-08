<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Grafik & Analitik Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Total Jenis Produk</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-2">
                        {{ isset($stokLabels) ? count($stokLabels) : 0 }} <span class="text-sm font-medium text-gray-500">Items</span>
                    </h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Total Volume Stok Tersedia</p>
                    <h3 class="text-3xl font-black text-indigo-600 mt-2">
                        {{ isset($stokValues) ? array_sum($stokValues) : 0 }} <span class="text-sm font-medium text-gray-500">Pcs</span>
                    </h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">📈 Grafik Estimasi Produk Terjual</h3>
                    <div class="relative h-[320px] w-full">
                        <canvas id="chartPenjualan"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">📦 Grafik Real-Time Sisa Stok Gudang</h3>
                    <div class="relative h-[320px] w-full">
                        <canvas id="chartStok"></canvas>
                    </div>
                </div>

            </div>

            <div class="flex justify-end mt-4">
                <a href="{{ route('pos.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-3 rounded-lg shadow transition-all">
                    🏪 Kembali Ke Menu Kasir (POS)
                </a>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Menangkap data asli dari database atau sampel fallback aman
            const rawLabels = {!! json_encode($stokLabels ?? ['Nasi Goreng', 'Mie Ayam', 'Ayam Goreng']) !!};
            const rawStok = {!! json_encode($stokValues ?? [50, 40, 35]) !!};
            const rawPenjualan = {!! json_encode($penjualanValues ?? [15, 25, 30]) !!};

            // 1. Pembuatan Grafik Batang Penjualan
            const ctxPenjualan = document.getElementById('chartPenjualan').getContext('2d');
            new Chart(ctxPenjualan, {
                type: 'bar',
                data: {
                    labels: rawLabels,
                    datasets: [{
                        label: 'Total Terjual (Qty)',
                        data: rawPenjualan,
                        backgroundColor: 'rgba(79, 70, 229, 0.7)',
                        borderColor: '#4f46e5',
                        borderWidth: 1.5,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });

            // 2. Pembuatan Grafik Garis Sisa Stok
            const ctxStok = document.getElementById('chartStok').getContext('2d');
            new Chart(ctxStok, {
                type: 'line',
                data: {
                    labels: rawLabels,
                    datasets: [{
                        label: 'Sisa Stok Tersedia',
                        data: rawStok,
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        borderColor: '#0ea5e9',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.25,
                        pointBackgroundColor: '#0ea5e9'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });
        });
    </script>
</x-app-layout>