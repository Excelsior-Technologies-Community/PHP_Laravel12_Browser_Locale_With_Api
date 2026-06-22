<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;

class LocaleApiController extends Controller
{

    protected $supportedLocales = [
        'en',
        'fr',
        'es'
    ];


    public function currentLocale()
    {
        return response()->json([
            'success' => true,
            'locale' => App::getLocale(),
            'detected_from' => 'Accept-Language header'
        ]);
    }


    public function languages()
    {
        return response()->json([
            'success' => true,
            'languages' => $this->supportedLocales
        ]);
    }


    public function translatedContent()
    {
        return response()->json([

            'success' => true,

            'locale' => App::getLocale(),

            'translations' => [

                'title' => __('messages.title'),

                'welcome' => __('messages.welcome'),

                'description' => __('messages.description'),

            ]

        ]);
    }
}