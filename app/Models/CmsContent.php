<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsContent extends Model
{
    protected $fillable = [
        'section',
        'key',
        'value',
        'type',
        'order',
    ];

    public static function get($section, $key, $default = '')
    {
        $content = static::where('section', $section)
            ->where('key', $key)
            ->first();
        
        return $content ? $content->value : $default;
    }

    public static function getSection($section)
    {
        return static::where('section', $section)
            ->orderBy('order')
            ->get()
            ->pluck('value', 'key');
    }
}
