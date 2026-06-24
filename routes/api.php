<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleApiController;

Route::get(
    '/locale',
    [LocaleApiController::class, 'currentLocale']
);

Route::get(
    '/languages',
    [LocaleApiController::class, 'languages']
);

Route::get(
    '/translations',
    [LocaleApiController::class, 'translatedContent']
);

Route::get(
    '/statistics',
    [LocaleApiController::class, 'statistics']
);

Route::get(
    '/history',
    [LocaleApiController::class, 'history']
);

Route::get(
    '/search-language/{keyword}',
    [LocaleApiController::class, 'searchLanguage']
);