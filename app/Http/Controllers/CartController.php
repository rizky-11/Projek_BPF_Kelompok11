<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(CartService $cart)
    {
        return view('cart', ['lines' => $cart->lines()]);
    }

    public function store(Request $request, CartService $cart)
    {
        $data = $request->validate([
            'variant_id' => 'required|integer|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:100000',
        ]);

        $variant = ProductVariant::with('product')->findOrFail($data['variant_id']);
        abort_unless($variant->is_active && $variant->product->is_active, 404);

        $qty = ($cart->raw()[$variant->id] ?? 0) + $data['quantity'];
        if ($error = $this->qtyError($variant, $qty)) {
            return back()->withErrors(['quantity' => $error])->withInput();
        }

        $cart->set($variant, $qty);

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, ProductVariant $variant, CartService $cart)
    {
        $data = $request->validate(['quantity' => 'required|integer|min:1|max:100000']);

        if ($error = $this->qtyError($variant, $data['quantity'])) {
            return back()->with('error', "{$variant->product->name} {$variant->label}: {$error}");
        }

        $cart->set($variant, $data['quantity']);

        return back()->with('success', 'Jumlah diperbarui.');
    }

    public function destroy(ProductVariant $variant, CartService $cart)
    {
        $cart->remove($variant->id);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    private function qtyError(ProductVariant $variant, int $qty): ?string
    {
        if ($qty < $variant->min_qty) {
            return "Minimal pemesanan {$variant->min_qty} {$variant->unit}.";
        }
        if ($qty > $variant->stock) {
            return "Stok tersisa {$variant->stock} {$variant->unit}.";
        }

        return null;
    }
}
