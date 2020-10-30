<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Models\Url;
use App\Http\Models\UrlStatistic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class HitStatistic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Url $url;
    protected string $userAgent;
    protected string $cacheKey;

    public function __construct(Url $url, string $userAgent, array $config)
    {
        $this->url = $url;
        $this->userAgent = $userAgent;
        $this->cacheKey = $config['cache_key'];
    }

    public function handle()
    {
        try {
            $key = Str::random(10);
            Cache::put($key, [
                'url_id' => $this->url->id,
                'date_time' => now(),
                'user_agent' => $this->userAgent,
            ]);
            $keys = Cache::get($this->cacheKey, []);
            $keys = Arr::prepend($keys, $key);
            Cache::put($this->cacheKey, $keys);
        } catch (Throwable $exception) {
            Log::error('Error while creating statistics: ' . $exception->getMessage());
        }
    }
}
