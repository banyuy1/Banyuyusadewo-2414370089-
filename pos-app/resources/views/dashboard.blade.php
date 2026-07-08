<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart POS - Premium Analytics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#0f0507] via-[#240a10] to-[#0f0507] text-slate-100 min-h-screen antialiased flex flex-col">

    <header class="bg-[#1a070b]/80 backdrop-blur-md border-b border-rose-950/60 sticky top-0 z-50 px-6 py-4 shadow-2xl">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div>
                <h1 class="font-extrabold text-xl tracking-tight text-white flex items-center gap-2">
                    <span class="text-rose-500">📊</span> Smart POS <span class="text-rose-400 font-semibold text-xs px-2 py-0.5 bg-rose-950/80 rounded-lg border border-rose-900/50">Analytics Mode</span>
                </h1>
                <p class="text-[11px] text-rose-300/60 mt-0.5">Grafik Penjualan Real-Time & Monitoring Sisa Stok</p>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-xs text-rose-200/70 font-medium">Kasir #01</span>
                <a href="{{ route('pos.index') }}" class="bg-gradient-to-r from-rose-700 to-amber-600 hover:from-rose-600 hover:to-amber-500 text-white text-xs font-extrabold px-5 py-2.5 rounded-xl border border-rose-500/20 shadow-lg shadow-rose-950/50 transition-all duration-200 flex items-center gap-1.5 transform hover:-translate-y-0.5">
                    <span>🏪</span> Ke Menu Kasir
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto w-full p-4 sm:p-6 lg:p-8 space-y-8 flex-1">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white/[0.02] backdrop-blur-md p-6 rounded-2xl shadow-xl border border-white/[0.05] relative overflow-hidden group transition-all duration-300 hover:border-rose-500/20">
                <div class="absolute -right-4 -bottom-4 text-7xl opacity-5 select-none">🍔</div>
                <p class="text-xs font-bold uppercase tracking-widest text-rose-300/60">Total Jenis Produk</p>
                <h3 class="text-4xl font-black text-white mt-3 tracking-tight">
                    {{ isset($stokLabels) ? count($stokLabels) : 0 }} 
                    <span class="text-sm font-medium text-rose-200/40">Menu Kuliner</span>
                </h3>
            </div>
            
            <div class="bg-white/[0.02] backdrop-blur-md p-6 rounded-2xl shadow-xl border border-white/[0.05] relative overflow-hidden group transition-all duration-300 hover:border-amber-500/20">
                <div class="absolute -right-4 -bottom-4 text-7xl opacity-5 select-none">📦</div>
                <p class="text-xs font-bold uppercase tracking-widest text-amber-300/60">Total Volume Stok Tersedia</p>
                <h3 class="text-4xl font-black text-amber-400 mt-3 tracking-tight">
                    {{ isset($stokValues) ? array_sum($stokValues) : 0 }} 
                    <span class="text-sm font-medium text-slate-500">Pcs</span>
                </h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-black/40 backdrop-blur-md p-6 rounded-3xl shadow-2xl border border-white/[0.05] space-y-4">
                <div class="flex items-center justify-between border-b border-white/[0.05] pb-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-rose-200 flex items-center gap-2">
                        <span class="text-rose-500">📈</span> Grafik Estimasi Produk Terjual
                    </h3>
                    <span class="text-[10px] bg-rose-500/20 text-rose-300 border border-rose-500/30 px-2 py-0.5 rounded-full font-bold">Bar Chart</span>
                </div>
                <div class="relative h-[340px] w-full">
                    <canvas id="chartPenjualan"></canvas>
                </div>
            </div>

            <div class="bg-black/40 backdrop-blur-md p-6 rounded-3xl shadow-2xl border border-white/[0.05] space-y-4">
                <div class="flex items-center justify-between border-b border-white/[0.05] pb-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-amber-200 flex items-center gap-2">
                        <span class="text-amber-500">🏪</span> Grafik Real-Time Sisa Stok Gudang
                    </h3>
                    <span class="text-[10px] bg-amber-500/20 text-amber-300 border border-amber-500/30 px-2 py-0.5 rounded-full font-bold">Line Chart</span>
                </div>
                <div class="relative h-[340px] w-full">
                    <canvas id="chartStok"></canvas>
                </div>
            </div>

        </div>
    </main>

    <footer class="text-center py-6 text-xs text-rose-300/30 border-t border-rose-950/30 mt-8">
        &copy; 2026 Smart POS System &bull; Premium Red Crimson Gradient v2.0
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Setup Text & Grid untuk Grafik Tema Gelap
            Chart.defaults.color = '#fda4af'; // rose-300
            Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.04)';

            const rawLabels = {!! json_encode($stokLabels ?? []) !!};
            const rawStok = {!! json_encode($stokValues ?? []) !!};
            const rawPenjualan = {!! json_encode($penjualanValues ?? []) !!};

            // 1. Grafik Penjualan (Merah Crimson Neon)
            const ctxPenjualan = document.getElementById('chartPenjualan').getContext('2d');
            new Chart(ctxPenjualan, {
                type: 'bar',
                data: {
                    labels: rawLabels,
                    datasets: [{
                        label: 'Total Terjual (Qty)',
                        data: rawPenjualan,
                        backgroundColor: 'rgba(225, 29, 72, 0.5)', // rose-600 dengan opasitas
                        borderColor: '#f43f5e', // rose-500 solid
                        borderWidth: 2,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(225, 29, 72, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, ticks: { color: '#f43f5e' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 2. Grafik Sisa Stok (Gold / Amber Neon)
            const ctxStok = document.getElementById('chartStok').getContext('2d');
            const gradientBg = ctxStok.createLinearGradient(0, 0, 0, 300);
            gradientBg.addColorStop(0, 'rgba(245, 158, 11, 0.25)'); // amber-500
            gradientBg.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

            new Chart(ctxStok, {
                type: 'line',
                data: {
                    labels: rawLabels,
                    datasets: [{
                        label: 'Sisa Stok Tersedia',
                        data: rawStok,
                        backgroundColor: gradientBg,
                        borderColor: '#f59e0b', // Amber Solid
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35, 
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#1a070b',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, ticks: { color: '#f59e0b' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</body>
</html>