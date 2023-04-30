<?php

namespace App\Http\Middleware;

use App\Models\Config;
use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = DEFAULT_LANG; // set en as the fallback locale
        if ($request->segment(1) == 'en') {
            $locale = 'en';
        }

        //set the derived locale
        App::setLocale($locale);
        \view()->share('lang', $locale);
        return $next($request);
    }
}
