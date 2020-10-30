<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Models\UrlStatistic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PersistStatistics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $cacheKey = 'cached_keys';

    public function handle()
    {
        try {
            DB::beginTransaction();

            $keys = Cache::get($this->cacheKey);

            $statistics = [];
            // возможно сделать получение всего файлика??
            foreach ($keys as $key) {
                $statistics[] = Cache::get($key);
            }

            UrlStatistic::insert($statistics);

            DB::commit();

            Cache::flush();
        } catch (Throwable $exception) {
            DB::rollBack();

            Log::error('Error while creating statistics: ' . $exception->getMessage());
        }
    }
}
