<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $table = 'app_settings';

    protected $fillable = ['key', 'value', 'type', 'group', 'label', 'description'];

    /**
     * Retrieve a setting value with 1-hour cache.
     */
    public static function getValue(string $key, $default = null)
    {
        return Cache::remember("app_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            if (!$setting) return $default;

            return match ($setting->type) {
                'number'  => is_numeric($setting->value) ? (float) $setting->value : $default,
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'json'    => json_decode($setting->value, true) ?? $default,
                default   => $setting->value ?? $default,
            };
        });
    }

    public static function setValue(string $key, $value): void
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            $setting->update([
                'value' => is_array($value) ? json_encode($value) : (string) $value,
            ]);
            Cache::forget("app_setting_{$key}");
        }
    }
}