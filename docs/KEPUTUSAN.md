# Catatan Keputusan Projek (Toko Kertas)

| Kode | Keputusan | Bila diubah, yang terdampak |
|---|---|---|
| D1 | Hanya admin yang punya akun dan login | Akun pelanggan: `users.role`, `orders.user_id`, halaman daftar/login pelanggan |
| D2 | Pemesanan tanpa akun (guest checkout) | Sama dengan D1 |
| D3 | Pembayaran transfer manual + unggah bukti | Payment gateway: kolom referensi di `payments`, webhook |
| D4 | Admin = Volt (Bootstrap 5), situs publik = Tailwind (Vite) | Layout `layouts/admin/*` atau `layouts/app` |
| D5 | Keranjang di session | Keranjang di database: tabel `carts` + `CartService` |
| D6 | Harga dihitung ulang di server dari varian | Tidak boleh diubah |
| D7 | Lacak pesanan dengan kode pesanan + nomor telepon | `Public\OrderController`, `orders/track` |
| D8 | Bukti bayar disimpan privat (`storage/app/private`), hanya admin yang bisa membuka | Tidak disarankan diubah |
| D9 | 11 tabel buatan sendiri + `users` | Lihat bagian Tabel |
| D10 | **Perubahan: ini toko KERTAS, bukan percetakan.** Tidak ada upload desain dan "finishing". Pilihan spesifikasi (`option_groups`, `option_values`) diganti **varian** (`product_variants`: ukuran, gramatur, satuan, harga, stok) | `products` tidak lagi punya harga dasar; harga ada di varian. Stok berkurang saat pesanan dibuat dan kembali saat dibatalkan |

## Tabel
`users` (admin), `categories`, `products`, `product_images`, `product_variants`, `orders`, `order_items`, `payments`, `contact_messages`, `faqs`, `settings`
(+ tabel bawaan Laravel: sessions, cache, jobs).

Relasi: kategori 1-N produk, produk 1-N varian, produk 1-N foto, pesanan 1-N item, pesanan 1-N pembayaran. `order_items` menyimpan **salinan** nama, label varian, satuan, dan harga saat pesanan dibuat.

## Alur status pesanan
`menunggu_pembayaran` -> (pelanggan unggah bukti) `menunggu_verifikasi` -> (admin verifikasi) `diproses` -> `siap` -> `selesai`.
Bukti ditolak: kembali ke `menunggu_pembayaran`. `dibatalkan` bisa dari status sebelum `selesai`, dan mengembalikan stok.

## CRUD
| Modul | Siapa | Operasi |
|---|---|---|
| Kategori | Admin | C R U D (hapus ditolak jika masih ada produk) |
| Produk + foto + varian | Admin | C R U D |
| Pesanan | Admin | R + ubah status/ongkir/catatan (tanpa hapus, agar riwayat utuh) |
| Pembayaran | Admin | verifikasi / tolak / lihat bukti |
| Pesan Hubungi Kami | Publik C, Admin R D | ditandai dibaca otomatis |
| FAQ | Admin | C R U D |
| Pengaturan toko | Admin | U |
| Password admin | Admin | U |

## Keterbatasan yang sengaja diterima
- Satu pembayaran per upaya; belum ada cicilan.
- Ongkos kirim diisi manual oleh admin (belum ada hitung otomatis/ekspedisi).
- Email notifikasi belum ada (`MAIL_MAILER=log`).
- Role admin tunggal: semua akun di tabel `users` adalah admin.
