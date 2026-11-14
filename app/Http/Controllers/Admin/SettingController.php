<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public const FIELDS = [
        'store_name' => 'Nama toko',
        'tagline' => 'Tagline',
        'hero_title' => 'Judul beranda',
        'hero_text' => 'Teks beranda',
        'address' => 'Alamat',
        'whatsapp' => 'WhatsApp (format 62812...)',
        'email' => 'Email',
        'open_hours' => 'Jam buka',
        'bank_name' => 'Nama bank',
        'bank_account' => 'Nomor rekening',
        'bank_holder' => 'Atas nama rekening',
        'shipping_note' => 'Catatan ongkos kirim',
    ];

    public function edit()
    {
        return view('admin.settings', ['fields' => self::FIELDS]);
    }

    public function update(Request $request)
    {
        $rules = collect(self::FIELDS)->map(fn () => 'nullable|string|max:500')->all();
        $data = $request->validate($rules);

        foreach (array_keys(self::FIELDS) as $key) {
            Setting::put($key, $data[$key] ?? null);
        }

        return back()->with('success', 'Pengaturan disimpan.');
    }
}
