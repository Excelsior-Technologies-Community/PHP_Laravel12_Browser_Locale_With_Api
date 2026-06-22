<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleApiController;


Route::get(
    '/locale',
    [LocaleApiController::class,'currentLocale']
);


Route::get(
    '/translations',
    [LocaleApiController::class,'translatedContent']
);


Route::get(
    '/languages',
    [LocaleApiController::class,'languages']
);