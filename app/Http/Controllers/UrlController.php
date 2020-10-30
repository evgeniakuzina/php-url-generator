<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Models\Url;
use App\Http\Requests\CreateShortUrlRequest;
use App\Jobs\HitStatistic;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class UrlController extends Controller
{
    public function createShortUrl(CreateShortUrlRequest $request): JsonResponse
    {
        try {
            $lifeTime = $request->get('life_time');

            $url = Url::create([
                'origin' => $request->get('url'),
                'expires_at' => $lifeTime ? Carbon::createFromTimestamp(time() + $lifeTime) : $lifeTime,
                'uid' => Str::random(10),
            ]);
        } catch (Throwable $exception) {
            Log::error('Error while creating short url: ' . $exception->getMessage());

            return response()->json(['error' => 'Error while creating short url'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['short_url' => $url->short_url], JsonResponse::HTTP_OK);
    }

    public function getUrlStatistics(string $uid): JsonResponse
    {
        try {
            /** @var Url $url */
            $url = Url::where('uid', $uid)->first();

            if (!$url) {
                return response()->json(['error' => 'Short url not found'], JsonResponse::HTTP_NOT_FOUND);
            }
        } catch (Throwable $exception) {
            Log::error('Error while hitting statistics: ' . $exception->getMessage());

            return response()->json(['error' => 'Error while hitting statistics'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([$url->short_url => $url->statistics->makeHidden(['id', 'url_id'])], JsonResponse::HTTP_OK);
    }

    public function hitUrlStatistics(string $uid, Request $request)
    {
        try {
            $url = Url::where('uid', $uid)->isValid()->first();

            if (!$url) {
                return response()->json(['error' => 'Short url not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            HitStatistic::dispatch($url, $request->header('user_agent'), config('cache'));
        } catch (Throwable $exception) {
            Log::error('Error while dispatching job: ' . $exception->getMessage());

            return response()->json(['error' => 'Error while hitting statistics'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return redirect($url->origin);
    }
}
