<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        $locale = $request->locale;

        if(in_array($locale , ['en', 'nl'])) // set the locales in app config file ==> config('app.locales') should return an array with the locales
        {
            App::setLocale($locale);
        } 
         
        return $next($request);
    }
}