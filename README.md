# Projek BPF: Sistem Pemesanan Kertas

Laravel 13 (PHP 8.3+). Situs publik: Blade + Tailwind 4 (Vite). Panel admin: template Volt (Bootstrap 5).
Fitur: katalog kategori dan produk dengan varian ukuran/gramatur/stok, keranjang, checkout tanpa akun, transfer manual + unggah bukti,
lacak pesanan, FAQ, Hubungi Kami, dan panel admin (kategori, produk, pesanan, verifikasi pembayaran, pesan, FAQ, pengaturan).
Keputusan desain dan daftar tabel: `docs/KEPUTUSAN.md`.

## Pasang (sekali saja)
1. Pasang PHP 8.3+, Composer, Node.js 20+, dan MySQL (mudah: Laragon).
2. Buat database kosong `toko_kertas` (collation `utf8mb4_unicode_ci`).
3. Di folder project:
```
composer install
copy .env.example .env        (Mac/Linux: cp .env.example .env)
php artisan key:generate
```
4. Buka `.env`, sesuaikan `DB_USERNAME` dan `DB_PASSWORD` bila perlu.
5. Lanjutkan:
```
php artisan migrate:fresh --seed
php artisan storage:link
npm install
```

## Jalankan (dua terminal)
```
php artisan serve
npm run dev
```
Situs: http://localhost:8000. Admin: http://localhost:8000/admin/login (`admin@example.com` / `password`). **Ganti password ini** lewat menu Ganti Password.

## Uji otomatis
```
php artisan test
```

## Peta folder
- `routes/web.php`: semua route (publik dan admin)
- `app/Http/Controllers/*`: sisi publik; `app/Http/Controllers/Admin/*`: panel admin
- `app/Http/Requests/*`: validasi form
- `app/Services/CartService.php`, `OrderService.php`: logika keranjang dan pembuatan pesanan (harga dihitung ulang di server, stok dikunci dalam transaksi)
- `app/Models/*`, `database/migrations/*`, `database/seeders/DatabaseSeeder.php`
- `resources/views/*` (publik) dan `resources/views/admin/*`, `layouts/admin/*` (admin)
- `lang/id/validation.php`: pesan validasi bahasa Indonesia
- `public/assets-admin/*`: aset template Volt

## Data contoh
Seeder mengisi 4 kategori, 7 produk berikut variannya (harga ilustrasi), FAQ, dan pengaturan toko. Ubah lewat panel admin.
