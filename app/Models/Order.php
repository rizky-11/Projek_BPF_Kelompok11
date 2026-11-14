<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUSES = [
        'menunggu_pembayaran' => ['Menunggu Pembayaran', 'warning'],
        'menunggu_verifikasi' => ['Menunggu Verifikasi', 'info'],
        'diproses' => ['Diproses', 'primary'],
        'siap' => ['Siap Diambil/Dikirim', 'secondary'],
        'selesai' => ['Selesai', 'success'],
        'dibatalkan' => ['Dibatalkan', 'danger'],
    ];

    /** Perpindahan status yang boleh dilakukan admin secara manual. */
    public const NEXT = [
        'menunggu_pembayaran' => ['dibatalkan'],
        'menunggu_verifikasi' => ['dibatalkan'],
        'diproses' => ['siap', 'dibatalkan'],
        'siap' => ['selesai', 'dibatalkan'],
        'selesai' => [],
        'dibatalkan' => [],
    ];

    protected $fillable = [
        'order_code', 'customer_name', 'customer_phone', 'customer_email', 'delivery_method',
        'shipping_address', 'customer_note', 'subtotal', 'shipping_cost', 'total', 'status', 'admin_note',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->latest();
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status][0] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUSES[$this->status][1] ?? 'secondary';
    }

    public function canBePaid(): bool
    {
        return $this->status === 'menunggu_pembayaran';
    }

    public static function generateCode(): string
    {
        do {
            $code = 'KRT-'.now()->format('ymd').'-'.Str::upper(Str::random(6));
        } while (static::where('order_code', $code)->exists());

        return $code;
    }

    /** 0812-3456-789 / +62 812 3456 789 -> 08123456789 */
    public static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        return str_starts_with($digits, '62') ? '0'.substr($digits, 2) : $digits;
    }
}
