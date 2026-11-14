<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;

/**
 * Keranjang belanja disimpan di session: [variant_id => jumlah].
 * Harga selalu diambil dari database, bukan dari browser.
 */
class CartService
{
    private const KEY = 'cart';

    public function raw(): array
    {
        return session(self::KEY, []);
    }

    public function count(): int
    {
        return count($this->raw());
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function add(ProductVariant $variant, int $qty): void
    {
        $cart = $this->raw();
        $cart[$variant->id] = ($cart[$variant->id] ?? 0) + $qty;
        session([self::KEY => $cart]);
    }

    public function set(ProductVariant $variant, int $qty): void
    {
        $cart = $this->raw();
        $cart[$variant->id] = $qty;
        session([self::KEY => $cart]);
    }

    public function remove(int $variantId): void
    {
        $cart = $this->raw();
        unset($cart[$variantId]);
        session([self::KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }

    /** @return Collection<int, object{variant: ProductVariant, qty: int, line_total: int, problem: ?string}> */
    public function lines(): Collection
    {
        $variants = ProductVariant::with('product.images')
            ->whereIn('id', array_keys($this->raw()))
            ->get()
            ->keyBy('id');

        return collect($this->raw())->map(function ($qty, $id) use ($variants) {
            $variant = $variants->get($id);
            if (! $variant || ! $variant->is_active || ! $variant->product->is_active) {
                return null;
            }

            $problem = null;
            if ($qty > $variant->stock) {
                $problem = "Stok tersisa {$variant->stock}.";
            } elseif ($qty < $variant->min_qty) {
                $problem = "Minimal pemesanan {$variant->min_qty}.";
            }

            return (object) [
                'variant' => $variant,
                'qty' => (int) $qty,
                'line_total' => $variant->price * $qty,
                'problem' => $problem,
            ];
        })->filter()->values();
    }

    public function subtotal(): int
    {
        return (int) $this->lines()->sum('line_total');
    }
}
