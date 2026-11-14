<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'sku', 'size_name', 'dimension', 'gsm', 'unit',
        'content', 'price', 'stock', 'min_qty', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** Contoh: "A4 · 80 gsm" */
    public function getLabelAttribute(): string
    {
        return implode(' · ', array_filter([$this->size_name, $this->gsm ? $this->gsm.' gsm' : null]));
    }

    /** Contoh: "Rim (500 lembar)" */
    public function getUnitLabelAttribute(): string
    {
        return ucfirst($this->unit).($this->content ? " ({$this->content})" : '');
    }
}
