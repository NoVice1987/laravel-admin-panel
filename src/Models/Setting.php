<?php

namespace StatisticLv\AdminPanel\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'title',
        'description',
        'options',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'number' => is_numeric($setting->value) ? (float) $setting->value : $default,
            'array' => json_decode($setting->value, true) ?? $default,
            default => $setting->value,
        };
    }

    /**
     * Set a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function setValue(string $key, $value): void
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return;
        }

        if (is_array($value)) {
            $value = json_encode($value);
        }

        $setting->value = $value;
        $setting->save();
    }

    /**
     * Get all settings grouped by group.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllGrouped()
    {
        return static::all()->groupBy('group');
    }

    /**
     * Get all settings as key-value pairs.
     *
     * @return array
     */
    public static function getAllAsArray()
    {
        return static::pluck('value', 'key')->toArray();
    }
}