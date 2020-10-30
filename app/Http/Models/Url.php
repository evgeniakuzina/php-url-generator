<?php

declare(strict_types=1);

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'origin',
        'uid',
        'expires_at',
    ];

    protected $hidden = [
        'uid',
    ];

    protected $appends = [
        'short_url',
    ];

    public function statistics()
    {
        return $this->hasMany(UrlStatistic::class);
    }

    public function getShortUrlAttribute()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $this->uid;
    }

    public function scopeIsValid(Builder $query)
    {
        return $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
    }
}
