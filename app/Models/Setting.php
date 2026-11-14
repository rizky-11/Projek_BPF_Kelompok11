<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    private static ?array $memo = null;

    public static function allValues(): array
    {
        return self::$memo ??= static::query()->pluck('value', 'key')->all();
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        return self::allValues()[$key] ?? $default;
    }

    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        self::$memo = null;
    }
}
