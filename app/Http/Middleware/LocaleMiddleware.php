<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        $locale = $request->locale;

        if(in_array($locale , ['en', 'nl'])) // set the locals in app config file ==> config('app.locales') should return an array with the locales
        {
            App::setLocale($locale);
        } else {
            $defaultLocale = config('app.locale');


            return redirect()->to(preg_replace("/^\/$locale\//", "/$defaultLocale/", request()->getRequestUri()));
        }
        
        return $next($request);
    }
}