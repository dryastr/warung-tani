<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('user.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);

        $lastProduct = Product::latest('id')->first();
        $nextNumber = $lastProduct ? (int)substr($lastProduct->code, 4) + 1 : 1;
        $newCode = 'TANI' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        Product::create([
            'code' => $newCode,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'discount' => $request->discount,
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'discount' => $request->discount,
        ]);

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
