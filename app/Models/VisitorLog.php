<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'user_id',
        'session_id',
        'url',
        'method',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'referrer',
        'country',
        'city',
        'region',
        'latitude',
        'longitude',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get device type from user agent
     */
    public static function detectDevice($userAgent): string
    {
        if (preg_match('/mobile/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Get browser from user agent
     */
    public static function detectBrowser($userAgent): string
    {
        if (preg_match('/MSIE|Trident/i', $userAgent)) return 'Internet Explorer';
        if (preg_match('/Edge/i', $userAgent)) return 'Edge';
        if (preg_match('/Firefox/i', $userAgent)) return 'Firefox';
        if (preg_match('/Chrome/i', $userAgent)) return 'Chrome';
        if (preg_match('/Safari/i', $userAgent)) return 'Safari';
        if (preg_match('/Opera|OPR/i', $userAgent)) return 'Opera';
        return 'Unknown';
    }

    /**
     * Get platform from user agent
     */
    public static function detectPlatform($userAgent): string
    {
        if (preg_match('/windows/i', $userAgent)) return 'Windows';
        if (preg_match('/macintosh|mac os x/i', $userAgent)) return 'Mac OS';
        if (preg_match('/linux/i', $userAgent)) return 'Linux';
        if (preg_match('/android/i', $userAgent)) return 'Android';
        if (preg_match('/iphone|ipad|ipod/i', $userAgent)) return 'iOS';
        return 'Unknown';
    }
}
