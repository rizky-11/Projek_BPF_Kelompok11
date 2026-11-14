<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin (GANTI password ini sebelum dipublikasikan)
        User::updateOrCreate(['email' => 'admin@example.com'], ['name' => 'Admin', 'password' => 'password']);

        $this->settings();
        $this->catalog();
        $this->faqs();
    }

    private function settings(): void
    {
        $defaults = [
            'store_name' => 'Toko Kertas',
            'tagline' => 'Kertas berbagai jenis dan ukuran, langsung pesan dari rumah.',
            'hero_title' => 'Semua kertas yang Anda butuhkan, dalam ukuran yang pas.',
            'hero_text' => 'HVS, art paper, karton, kraft, hingga kertas foto. Pilih jenis, gramatur, dan ukuran, lalu bayar lewat transfer.',
            'address' => 'Jl. Contoh No. 1, Pekanbaru, Riau',
            'whatsapp' => '6281200000000',
            'email' => 'halo@tokokertas.test',
            'open_hours' => 'Senin - Sabtu, 08.00 - 17.00 WIB',
            'bank_name' => 'BCA',
            'bank_account' => '1234567890',
            'bank_holder' => 'Toko Kertas',
            'shipping_note' => 'Ongkos kirim dihitung admin setelah pesanan masuk. Ambil di toko tanpa biaya.',
        ];
        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    private function catalog(): void
    {
        // Data contoh (harga ilustrasi). unit, isi, harga, stok, min pesan
        $data = [
            'Kertas Cetak & Fotokopi' => [
                ['HVS Sinar Dunia', 'Sinar Dunia', 'Kertas serbaguna untuk fotokopi, printer laser dan inkjet.', 'Warna: putih. Cocok untuk dokumen, tugas, laporan.', [
                    ['A4', '210 x 297 mm', 70, 'rim', '500 lembar', 42000, 120, 1],
                    ['A4', '210 x 297 mm', 80, 'rim', '500 lembar', 52000, 90, 1],
                    ['F4', '215 x 330 mm', 70, 'rim', '500 lembar', 49000, 60, 1],
                    ['A3', '297 x 420 mm', 70, 'rim', '500 lembar', 84000, 30, 1],
                ]],
                ['Kertas Warna Pastel', null, 'Kertas fotokopi berwarna untuk undangan, brosur, dan dokumen.', 'Pilihan warna: kuning, biru, hijau, pink.', [
                    ['A4', '210 x 297 mm', 80, 'pack', '100 lembar', 28000, 80, 1],
                    ['F4', '215 x 330 mm', 80, 'pack', '100 lembar', 33000, 50, 1],
                ]],
            ],
            'Kertas Art' => [
                ['Art Paper', null, 'Kertas berlapis halus dan mengilap untuk brosur, katalog, dan majalah.', 'Permukaan glossy, dua sisi.', [
                    ['A4', '210 x 297 mm', 120, 'pack', '100 lembar', 38000, 70, 1],
                    ['A3+', '320 x 480 mm', 120, 'pack', '100 lembar', 95000, 40, 1],
                    ['A3+', '320 x 480 mm', 150, 'pack', '100 lembar', 120000, 35, 1],
                ]],
                ['Art Carton', null, 'Karton tebal berlapis untuk kartu nama, cover, dan kemasan ringan.', 'Satu sisi glossy, sisi lain putih.', [
                    ['A3+', '320 x 480 mm', 230, 'pack', '100 lembar', 185000, 25, 1],
                    ['A3+', '320 x 480 mm', 260, 'pack', '100 lembar', 210000, 20, 1],
                ]],
            ],
            'Kertas Karton & Kemasan' => [
                ['Duplex', null, 'Karton abu-abu dengan sisi putih untuk kotak kemasan dan pembatas buku.', 'Sisi depan putih, sisi belakang abu-abu.', [
                    ['A3', '297 x 420 mm', 250, 'pack', '100 lembar', 98000, 30, 1],
                    ['A3', '297 x 420 mm', 310, 'pack', '100 lembar', 120000, 25, 1],
                ]],
                ['Kertas Kraft Coklat', null, 'Kertas coklat alami untuk bungkus, kemasan, dan kerajinan.', 'Ramah lingkungan, tidak dilapisi.', [
                    ['A3', '297 x 420 mm', 125, 'pack', '50 lembar', 45000, 60, 1],
                    ['A2', '420 x 594 mm', 125, 'pack', '50 lembar', 85000, 40, 1],
                ]],
            ],
            'Kertas Khusus' => [
                ['Kertas Foto Glossy', null, 'Kertas foto mengilap untuk printer inkjet, hasil tajam dan cepat kering.', 'Tahan air.', [
                    ['4R', '102 x 152 mm', 135, 'pack', '20 lembar', 18000, 100, 1],
                    ['A4', '210 x 297 mm', 135, 'pack', '20 lembar', 25000, 80, 1],
                ]],
            ],
        ];

        $order = 0;
        foreach ($data as $categoryName => $products) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName, 'sort_order' => $order++, 'is_active' => true]
            );

            foreach ($products as [$name, $brand, $desc, $spec, $variants]) {
                $product = Product::updateOrCreate(['slug' => Str::slug($name)], [
                    'category_id' => $category->id, 'name' => $name, 'brand' => $brand,
                    'description' => $desc, 'specification' => $spec, 'is_active' => true,
                ]);

                foreach ($variants as [$size, $dim, $gsm, $unit, $content, $price, $stock, $min]) {
                    $product->variants()->updateOrCreate(
                        ['size_name' => $size, 'gsm' => $gsm],
                        ['dimension' => $dim, 'unit' => $unit, 'content' => $content,
                         'price' => $price, 'stock' => $stock, 'min_qty' => $min, 'is_active' => true]
                    );
                }
            }
        }
    }

    private function faqs(): void
    {
        $faqs = [
            ['Bagaimana cara memesan?', 'Pilih produk dan varian (ukuran dan gramatur), masukkan ke keranjang, isi data pemesan di halaman checkout, lalu lakukan transfer dan unggah bukti pembayaran.'],
            ['Apakah harus membuat akun?', 'Tidak perlu. Anda cukup mengisi nama dan nomor telepon. Simpan kode pesanan untuk melacak status pesanan.'],
            ['Bagaimana cara membayar?', 'Pembayaran lewat transfer bank ke rekening yang tertera di halaman pesanan. Setelah transfer, unggah bukti pembayaran di halaman yang sama.'],
            ['Berapa ongkos kirim?', 'Ambil di toko gratis. Untuk pengantaran, ongkos kirim dihitung admin dan akan terlihat di halaman pesanan Anda.'],
            ['Bagaimana melacak pesanan?', 'Buka menu Lacak Pesanan, lalu masukkan kode pesanan dan nomor telepon yang Anda pakai saat memesan.'],
            ['Apa arti gramatur (gsm)?', 'Gramatur adalah berat kertas per meter persegi. Semakin besar angkanya, semakin tebal dan kaku kertasnya.'],
        ];
        foreach ($faqs as $i => [$q, $a]) {
            Faq::updateOrCreate(['question' => $q], ['answer' => $a, 'sort_order' => $i, 'is_active' => true]);
        }
    }
}
