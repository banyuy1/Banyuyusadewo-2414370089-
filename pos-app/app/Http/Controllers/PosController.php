<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PosController extends Controller
{
    // 1. Menampilkan Halaman Utama Mesin Kasir (POS)
    public function index()
    {
        $products = Product::all();
        $cart = session()->get('cart', []);

        return view('pos.index', compact('products', 'cart'));
    }

    // 2. Menambahkan Produk ke dalam Keranjang Belanja Sementara
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Stok produk sudah habis!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['qty'] + 1 > $product->stock) {
                return redirect()->back()->with('error', 'Jumlah belanja melebihi sisa stok yang tersedia!');
            }
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                "name"  => $product->name,
                "qty"   => 1,
                "price" => $product->price,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back();
    }

    // 3. Proses Checkout Pembayaran dan Pengurangan Stok Fisik
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja masih kosong!');
        }

        // Hitung total tagihan belanjaan
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $cashReceived = $request->input('cash_received');

        if ($cashReceived < $total) {
            return redirect()->back()->with('error', 'Uang tunai yang diterima kurang dari total tagihan!');
        }

        // Mulai kurangi stok produk di database secara nyata
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $product->stock = max(0, $product->stock - $item['qty']);
                $product->save();
            }
        }

        $change = $cashReceived - $total;

        // Bersihkan keranjang setelah transaksi berhasil
        session()->forget('cart');

        // Kirim data kembalian ke halaman kasir untuk cetak struk sementara
        return redirect()->route('pos.index')->with([
            'success' => true,
            'total'   => $total,
            'cash'    => $cashReceived,
            'change'  => $change
        ]);
    }

    // 4. Mengosongkan/Reset Keranjang Belanja Sementara
    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('pos.index');
    }

    // 5. MENAMPILKAN HALAMAN DASHBOARD GRAFIK REAL-TIME & SISA STOK GUDANG
    public function analyticsDashboard()
    {
        // Mengambil data semua nama produk dan sisa stok langsung dari database
        $products = Product::select('name', 'stock')->get();
        
        $stokLabels = $products->pluck('name')->toArray();
        $stokValues = $products->pluck('stock')->toArray();

        // Membuat data performa penjualan (Simulasi dinamis berdasarkan sisa stok)
        $penjualanLabels = $stokLabels; 
        $penjualanValues = array_map(function($stok) {
            // Logika visual: Semakin sedikit stok tersisa, grafik penjualan akan terlihat semakin tinggi
            return max(2, 60 - $stok); 
        }, $stokValues);

        return view('dashboard', compact('stokLabels', 'stokValues', 'penjualanLabels', 'penjualanValues'));
    }
}