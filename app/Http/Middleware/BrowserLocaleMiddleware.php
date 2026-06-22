<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class BrowserLocaleMiddleware
{
    protected $supportedLocales = [
        'en',
        'fr',
        'es'
    ];

    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $language = $request->header('Accept-Language');

        if ($language) {

            $locale = strtolower(
                substr(explode(',', $language)[0], 0, 2)
            );

            if (
                in_array(
                    $locale,
                    $this->supportedLocales
                )
            ) {
                App::setLocale($locale);
            }
        }

        return $next($request);
    }
}