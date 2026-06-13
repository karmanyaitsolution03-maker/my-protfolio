<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;
    protected $fillable = ['key', 'value'];

    /** Return all settings as ['key' => 'value'] array. */
    public static function map(): array
    {
        return static::pluck('value', 'key')->toArray();
    }

    /** Grouped field definitions from config/portfolio.php. */
    public static function groups(): array
    {
        return config('portfolio', []);
    }

    /** Flat ['key' => 'default value'] map from the config definitions. */
    public static function defaults(): array
    {
        $out = [];
        foreach (static::groups() as $fields) {
            foreach ($fields as $key => $def) {
                $out[$key] = $def[1] ?? '';
            }
        }
        return $out;
    }

    /**
     * Config defaults overlaid with stored DB values — every known key is
     * always present, so views can read $settings['key'] without fallbacks.
     */
    public static function resolved(): array
    {
        return array_merge(static::defaults(), static::map());
    }

    /** Create any config keys missing from the DB (keeps existing edits). */
    public static function syncDefaults(): void
    {
        foreach (static::defaults() as $key => $value) {
            static::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
