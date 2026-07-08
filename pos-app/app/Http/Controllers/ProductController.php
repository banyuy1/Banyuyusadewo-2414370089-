<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Menggunakan Eager Loading 'with' agar query lebih ringan
        $products = Product::with('category')->get(); 
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all(); // Untuk opsi dropdown kategori di form
        return view('products.create', compact('categories')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer'
        ]);

        Product::create($request->all());
        return redirect()->route('products.index');
    }
}