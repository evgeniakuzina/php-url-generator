<?php

declare(strict_types=1);

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UrlStatistic extends Model
{
    protected $fillable = [
        'url_id',
        'date_time',
        'user_agent',
    ];

    public $timestamps = false;

    public function url()
    {
        return $this->belongsTo(Url::class);
    }
}
