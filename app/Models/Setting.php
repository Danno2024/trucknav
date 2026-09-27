<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static function tableExists(): bool
    {
        // Any connection failure (missing SQLite file, unreachable host,
        // migrations not run yet) means "no settings" — never throw,
        // so pre-install boot and maintenance checks stay safe.
        try {
            return Schema::hasTable('settings');
        } catch (\Throwable $e) {
            return false;
        }
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
