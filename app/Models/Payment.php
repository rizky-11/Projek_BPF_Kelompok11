<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'sender_name', 'amount', 'proof_path', 'status',
        'reject_reason', 'paid_at', 'verified_at', 'verified_by',
    ];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime', 'verified_at' => 'datetime'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
