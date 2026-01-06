<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

  protected $fillable = [
        'key',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @return Setting
     */
    public static function setValue($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get multiple settings by keys.
     *
     * @param array $keys
     * @return \Illuminate\Support\Collection
     */
    public static function getMultiple(array $keys)
    {
        return self::whereIn('key', $keys)->pluck('value', 'key');
    }

    /**
     * Set multiple settings at once.
     *
     * @param array $settings
     * @return void
     */
    public static function setMultiple(array $settings)
    {
        foreach ($settings as $key => $value) {
            self::setValue($key, $value);
        }
    }

    /**
     * Check if setting exists.
     *
     * @param string $key
     * @return bool
     */
    public static function exists($key)
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Delete setting by key.
     *
     * @param string $key
     * @return bool
     */
    public static function deleteByKey($key)
    {
        return self::where('key', $key)->delete();
    }

    /**
     * Get all settings as key-value pairs.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllAsKeyValue()
    {
        return self::pluck('value', 'key');
    }

    /**
     * Scope to search by key.
     */
    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }

    /**
     * Scope to search by partial key.
     */
    public function scopeByKeyLike($query, $key)
    {
        return $query->where('key', 'like', '%' . $key . '%');
    }

    /**
     * Get formatted key for display.
     */
    public function getFormattedKeyAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->key));
    }

    /**
     * Get value length.
     */
    public function getValueLengthAttribute()
    {
        return strlen($this->value);
    }

       public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get truncated value for display.
     */
    public function getTruncatedValueAttribute($length = 50)
    {
        return strlen($this->value) > $length 
            ? substr($this->value, 0, $length) . '...' 
            : $this->value;
    }
}
