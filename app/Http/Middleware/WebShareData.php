<?php

namespace App\Http\Middleware;

use App\Models\Blog;
use App\Models\Career;
use App\Models\Config;
use Closure;

class WebShareData
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = DEFAULT_LANG; // set en as the fallback locale
        if ($request->segment(1) == 'en') {
            $locale = 'en';
        }

//        \view()->share('config', Config::lang($locale)->first());
        \view()->share('lang', $locale);

        return $next($request);
    }
}
