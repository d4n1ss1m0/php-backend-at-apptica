<?php

namespace App\Http\Middleware;

use App\Traits\HttpResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class RateLimitByIp
{
    use HttpResponseTrait;

    // Максимальное количество запросов
    protected int $maxAttempts = 5;

    // Время действия лимита в секундах
    protected int $decaySeconds = 60;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //У ларавел есть встроенный рейтлимметр, но решил использовать redis
        $ip = $request->ip();
        $routeName = $request->path();
        $key = "rate_limit:{$routeName}:{$ip}";

        $current = Redis::get($key);

        if ($current && $current >= $this->maxAttempts) {
            $ttl = Redis::ttl($key);
            Log::error('Too many requests');
            return $this->error("Too many requests. Try again in {$ttl} seconds.", 'Too many requests.', Response::HTTP_FORBIDDEN);
        }

        Redis::incr($key);
        Redis::expire($key, $this->decaySeconds);

        return $next($request);
    }
}
