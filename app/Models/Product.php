<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'brand', 'description', 'specification', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('price');
    }

    /** Produk yang tampil di toko: aktif, kategori aktif, punya minimal 1 varian aktif. */
    public function scopeOnSale(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereHas('category', fn ($c) => $c->where('is_active', true))
            ->whereHas('variants', fn ($v) => $v->where('is_active', true));
    }

    /** Menambah atribut `starting_price` (harga varian aktif termurah). */
    public function scopeWithStartingPrice(Builder $query): Builder
    {
        return $query->withMin(['variants as starting_price' => fn ($v) => $v->where('is_active', true)], 'price');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            $image = $this->images->first();

            return $image ? asset('storage/'.$image->path) : asset('images/NoImage.jpg');
        });
    }
}
