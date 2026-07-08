<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart POS - Premium Cashier</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#14112e] text-slate-100 min-h-screen font-sans antialiased selection:bg-sky-500 selection:text-white">

    <header class="bg-[#1e1a42] border-b border-indigo-950/60 sticky top-0 z-50 px-6 py-4 shadow-xl">
        <div class="max-w-[1600px] mx-auto flex justify-between items-center">
            <div>
                <h1 class="font-black text-xl tracking-tight text-white flex items-center gap-2">
                    <span class="text-2xl">✨</span> Smart POS <span class="text-sky-400 font-medium text-sm px-2 py-0.5 bg-indigo-950/80 rounded-lg border border-indigo-900/50">Premium Mode</span>
                </h1>
                <p class="text-[11px] text-indigo-300/60 mt-0.5">Sistem Kasir Digital Ringan & Cepat</p>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('analytics.index') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-black px-4 py-2.5 rounded-xl border border-indigo-500/20 shadow-lg shadow-indigo-950 transition-all flex items-center gap-1.5">
                    <span>📊</span> Dashboard Grafik
                </a>

                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-white leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-sky-400 font-mono mt-1">ID KASIR: #0{{ auth()->user()->id }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-rose-950/40 hover:bg-rose-900/40 border border-rose-900/50 text-rose-400 text-xs font-bold px-3 py-2 rounded-xl transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-[1600px] mx-auto p-6 grid grid-cols-1 xl:grid-cols-4 gap-6 items-start">
        
        <div class="xl:col-span-3 space-y-5">
            
            <div class="bg-[#1e1a42] border border-indigo-900/40 p-4 rounded-2xl flex justify-between items-center shadow-lg">
                <div class="flex items-center gap-3">
                    <span class="text-lg">🍱</span>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-200">Katalog Menu Kuliner</h3>
                </div>
                <span class="bg-[#14112e] text-sky-400 px-3 py-1 rounded-xl text-xs font-black border border-indigo-900/60">
                    {{ count($products) }} Items Ready
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($products as $product)
                <div class="bg-[#1e1a42] border border-indigo-900/50 p-5 rounded-3xl hover:border-sky-500/50 hover:shadow-xl hover:shadow-sky-950/30 transition-all duration-300 flex flex-col justify-between min-h-[190px] relative group overflow-hidden">
                    
                    <div class="absolute top-4 right-4 font-mono text-[11px] font-bold">
                        @if($product->stock > 10)
                            <span class="text-slate-400 bg-[#14112e] px-2 py-0.5 rounded-lg border border-indigo-900/40">{{ $product->stock }}</span>
                        @elseif($product->stock > 0)
                            <span class="text-amber-400 bg-amber-950/40 px-2 py-0.5 rounded-lg border border-amber-900/30">Sisa {{ $product->stock }}</span>
                        @else
                            <span class="text-rose-400 bg-rose-950/40 px-2 py-0.5 rounded-lg border border-rose-900/30">Habis</span>
                        @endif
                    </div>

                    <div class="pt-4 pr-10">
                        <h4 class="font-bold text-white text-base tracking-wide leading-snug group-hover:text-sky-400 transition-colors line-clamp-2">
                            {{ $product->name }}
                        </h4>
                        <p class="text-sky-400 font-black text-xl mt-3 tracking-tight">
                            <span class="text-xs font-bold text-sky-500/70 mr-0.5">Rp</span>{{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                    
                    <form action="{{ route('pos.add', $product->id) }}" method="POST" class="mt-6 w-full">
                        @csrf
                        <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }}
                            class="w-full {{ $product->stock <= 0 ? 'bg-indigo-950 text-indigo-800 cursor-not-allowed border border-indigo-900/20' : 'bg-sky-500 hover:bg-sky-600 text-white shadow-lg shadow-sky-950/40 font-bold' }} py-3 px-4 rounded-xl text-xs uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-1">
                            <span>+</span> Tambah
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-4 lg:sticky lg:top-[90px]">
            
            @if(session('error'))
                <div class="bg-rose-950/40 border border-rose-900/50 text-rose-400 p-4 rounded-2xl text-xs font-bold shadow-md">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-gradient-to-br from-emerald-600 to-teal-800 text-white p-5 rounded-2xl shadow-xl border border-emerald-500/20 space-y-3">
                    <div class="text-center font-mono">
                        <h4 class="font-black text-xs tracking-widest uppercase text-emerald-200">Transaksi Sukses</h4>
                    </div>
                    <div class="border-t border-white/10 border-dashed my-1"></div>
                    <div class="space-y-1.5 font-mono text-xs opacity-90">
                        <div class="flex justify-between"><span>Total Tagihan:</span><span>Rp {{ number_format(session('total'), 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span>Uang Tunai:</span><span>Rp {{ number_format(session('cash'), 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-sm font-black bg-black/20 p-2.5 rounded-xl mt-1 text-emerald-300">
                            <span>Kembalian:</span>
                            <span>Rp {{ number_format(session('change'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-[#1e1a42] rounded-3xl border border-indigo-900/50 shadow-2xl overflow-hidden">
                <div class="p-4 border-b border-indigo-900/40 bg-[#161233] flex justify-between items-center">
                    <h3 class="font-bold text-indigo-200 text-xs uppercase tracking-wider flex items-center gap-2">
                        🛒 Struk Belanja Sementara
                    </h3>
                    @if(count($cart) > 0)
                        <a href="{{ route('pos.clear') }}" class="text-[10px] font-bold text-rose-400 bg-rose-950/30 hover:bg-rose-950/60 px-2.5 py-1 rounded-lg border border-rose-900/30 transition">
                            Reset
                        </a>
                    @endif
                </div>
                
                @if(count($cart) > 0)
                    <div class="p-4 divide-y divide-indigo-900/30 max-h-[260px] overflow-y-auto custom-scrollbar bg-[#161233]/40">
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $item)
                        @php $total += $item['price'] * $item['qty']; @endphp
                        <div class="py-3 flex justify-between items-center gap-4 first:pt-0 last:pb-0">
                            <div class="max-w-[70%]">
                                <p class="font-bold text-white text-xs tracking-wide truncate">{{ $item['name'] }}</p>
                                <p class="text-indigo-400 text-[11px] mt-0.5">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }} <span class="text-sky-400 font-extrabold ml-1">x{{ $item['qty'] }}</span>
                                </p>
                            </div>
                            <span class="font-black text-slate-200 text-xs shrink-0">
                                Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <div class="p-4 bg-[#161233] border-t border-indigo-900/40 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Total Tagihan:</span>
                            <span class="text-2xl font-black text-sky-400 tracking-tight">
                                <span class="text-xs font-bold text-sky-500 mr-0.5">Rp</span>{{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        <form action="{{ route('pos.checkout') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-indigo-400 text-[10px] font-bold uppercase tracking-wider mb-2">
                                    Nominal Uang Tunai Diterima
                                </label>
                                
                                <div class="flex rounded-xl overflow-hidden border border-indigo-800 bg-[#0f0c24] focus-within:ring-2 focus-within:ring-sky-500 transition-all">
                                    <div class="bg-[#241f54] px-4 flex items-center justify-center font-black text-indigo-300 border-r border-indigo-800 text-xs select-none">
                                        Rp
                                    </div>
                                    <input type="text" id="input_mask_money" placeholder="0" required autocomplete="off"
                                        class="w-full bg-transparent border-0 px-3 py-3 font-black text-xl text-white focus:outline-none focus:ring-0">
                                </div>
                                
                                <input type="hidden" name="cash_received" id="cash_received_raw">
                            </div>

                            <button type="submit" 
                                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-950 transition-all tracking-wider text-center text-xs uppercase border border-indigo-500/20">
                                🚀 Simpan & Cetak Struk
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-16 px-4 bg-[#161233]/20">
                        <span class="text-3xl block mb-2">📥</span>
                        <h5 class="text-indigo-300 font-bold text-xs">Belum Ada Transaksi</h5>
                        <p class="text-indigo-400/50 text-[10px] mt-1 max-w-[180px] mx-auto leading-relaxed">
                            Pilih menu makanan di sebelah kiri untuk memulai pencatatan.
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const displayInput = document.getElementById('input_mask_money');
            const rawInput = document.getElementById('cash_received_raw');

            if(displayInput) {
                displayInput.addEventListener('input', function(e) {
                    let numberString = this.value.replace(/[^0-9]/g, '');
                    rawInput.value = numberString;

                    if (numberString) {
                        let formatted = parseInt(numberString).toLocaleString('id-ID');
                        this.value = formatted;
                    } else {
                        this.value = '';
                    }
                });
            }
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #161233; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #2d2669; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #3c338a; }
    </style>
</body>
</html>