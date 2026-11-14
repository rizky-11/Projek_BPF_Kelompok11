<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    /**
     * Membuat pesanan dari isi keranjang.
     * Semua harga dihitung ulang dari database dan stok dikunci selama transaksi.
     */
    public function createFromCart(array $data, CartService $cart): Order
    {
        $raw = $cart->raw();
        if (! $raw) {
            throw ValidationException::withMessages(['cart' => 'Keranjang masih kosong.']);
        }

        return DB::transaction(function () use ($data, $raw, $cart) {
            $variants = ProductVariant::with('product')
                ->whereIn('id', array_keys($raw))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $items = [];
            $subtotal = 0;

            foreach ($raw as $id => $qty) {
                $variant = $variants->get($id);
                $name = $variant?->product?->name.' '.$variant?->label;

                if (! $variant || ! $variant->is_active || ! $variant->product->is_active) {
                    throw ValidationException::withMessages(['cart' => "Produk {$name} sudah tidak tersedia. Hapus dari keranjang."]);
                }
                if ($qty < $variant->min_qty) {
                    throw ValidationException::withMessages(['cart' => "{$name}: minimal pemesanan {$variant->min_qty}."]);
                }
                if ($qty > $variant->stock) {
                    throw ValidationException::withMessages(['cart' => "{$name}: stok tersisa {$variant->stock}."]);
                }

                $lineTotal = $variant->price * $qty;
                $subtotal += $lineTotal;
                $items[] = [
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_label' => $variant->label,
                    'unit' => $variant->unit_label,
                    'unit_price' => $variant->price,
                    'quantity' => $qty,
                    'line_total' => $lineTotal,
                ];
                $variant->decrement('stock', $qty);
            }

            $order = Order::create([
                'order_code' => Order::generateCode(),
                'customer_name' => $data['customer_name'],
                'customer_phone' => Order::normalizePhone($data['customer_phone']),
                'customer_email' => $data['customer_email'] ?? null,
                'delivery_method' => $data['delivery_method'],
                'shipping_address' => $data['delivery_method'] === 'antar' ? $data['shipping_address'] : null,
                'customer_note' => $data['customer_note'] ?? null,
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'total' => $subtotal,
                'status' => 'menunggu_pembayaran',
            ]);
            $order->items()->createMany($items);

            $cart->clear();

            return $order;
        });
    }

    /** Membatalkan pesanan dan mengembalikan stok. */
    public function cancel(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items()->whereNotNull('product_variant_id')->get() as $item) {
                ProductVariant::whereKey($item->product_variant_id)->increment('stock', $item->quantity);
            }
            $order->update(['status' => 'dibatalkan']);
        });
    }
}
