<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_load(): void
    {
        foreach (['/', '/produk', '/faq', '/hubungi-kami', '/lacak-pesanan', '/keranjang', '/admin/login'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_pages_require_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/orders')->assertRedirect('/admin/login');
    }

    public function test_checkout_creates_order_and_reduces_stock(): void
    {
        $variant = ProductVariant::firstOrFail();
        $stock = $variant->stock;

        $this->post('/keranjang', ['variant_id' => $variant->id, 'quantity' => 2])->assertRedirect('/keranjang');

        $this->post('/checkout', [
            'customer_name' => 'Budi',
            'customer_phone' => '0812-3456-7890',
            'delivery_method' => 'ambil',
        ]);

        $order = Order::firstOrFail();
        $this->assertSame($variant->price * 2, $order->total);
        $this->assertSame('menunggu_pembayaran', $order->status);
        $this->assertSame($stock - 2, $variant->fresh()->stock);
    }

    public function test_quantity_above_stock_is_rejected(): void
    {
        $variant = ProductVariant::firstOrFail();

        $this->from('/produk')
            ->post('/keranjang', ['variant_id' => $variant->id, 'quantity' => $variant->stock + 1])
            ->assertSessionHasErrors('quantity');
    }
}
