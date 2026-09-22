<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static function tableExists(): bool
    {
        return Schema::hasTable('settings');
    }

    public static function get(string $key, $default = null)
    {
        if (!static::tableExists()) return $default;
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        if (!static::tableExists()) return;
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        return (bool) static::get($key, $default ? '1' : '0');
    }
}
