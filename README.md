# PHP_Laravel12_Browser_Locale_With_Api

## Introduction

A Laravel 12 API-based localization application that automatically detects a client's preferred language using the HTTP `Accept-Language` header and dynamically sets the application locale.

This project demonstrates how Laravel APIs can implement multilingual support without a user interface by detecting browser or API client language preferences and returning translated JSON responses.

The application uses middleware to inspect the `Accept-Language` request header, determine the appropriate locale, and load translation files dynamically.

---

## Features

* Automatic language detection using the `Accept-Language` header
* Dynamic locale switching for API responses
* Support for multiple languages
* Middleware-based locale handling
* Laravel localization and translation files
* JSON API responses
* Stateless API architecture
* Browser and API client language detection
* Laravel 12 compatible
* Easily extendable with additional languages

---

## Requirements

* PHP 8.2+
* Composer
* Laravel 12
* XAMPP / Laragon

---

# Create Project

## Step 1: Create Laravel 12 Project

```bash
composer create-project laravel/laravel PHP_Laravel12_Browser_Locale_With_Api "12.*"
```

Move into project directory:

```bash
cd PHP_Laravel12_Browser_Locale_With_Api
```

---

## Step 2: Create Controller

Generate controller:

```bash
php artisan make:controller LocaleApiController
```

File:

```text
app/Http/Controllers/LocaleApiController.php
```

Code:

```php
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
```

---

## Step 3: Create Middleware

Generate middleware:

```bash
php artisan make:middleware BrowserLocaleMiddleware
```

File:

```text
app/Http/Middleware/BrowserLocaleMiddleware.php
```

Code:

```php
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
```

---

## Step 4: Register Middleware

Open:

```text
bootstrap/app.php
```

Update:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(
    basePath: dirname(__DIR__)
)

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->api(append: [

            \App\Http\Middleware\BrowserLocaleMiddleware::class,

        ]);

    })

    ->withExceptions(function (Exceptions $exceptions): void {

        //
        
    })->create();
```

---

## Step 5: Create Language Files

Create folders:

```text
lang
├── en
├── fr
└── es
```

---

### English

File:

```text
lang/en/messages.php
```

```php
<?php

return [

    'title' => 'Browser Locale Demo',

    'welcome' => 'Welcome to Laravel Browser Locale Project',

    'description' =>
        'The application automatically detects your browser language.',

];
```

---

### French

File:

```text
lang/fr/messages.php
```

```php
<?php

return [

    'title' => 'Démonstration Browser Locale',

    'welcome' =>
        'Bienvenue dans le projet Laravel Browser Locale',

    'description' =>
        'L’application détecte automatiquement la langue du navigateur.',

];
```

---

### Spanish

File:

```text
lang/es/messages.php
```

```php
<?php

return [

    'title' => 'Demostración Browser Locale',

    'welcome' =>
        'Bienvenido al proyecto Laravel Browser Locale',

    'description' =>
        'La aplicación detecta automáticamente el idioma del navegador.',

];
```

---

## Step 6: Create API Routes

Open:

```text
routes/api.php
```

Replace contents with:

```php
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
```

---

## Step 7: Run Application

Start server:

```bash
php artisan serve
```

Application URL:

```text
http://127.0.0.1:8000
```

---

# API Endpoints

## 1. Detect Browser Locale

### Request

```http
GET /api/locale
```

### Header

```http
Accept-Language: fr
```

### Response

```json
{
    "success": true,
    "locale": "fr",
    "detected_from": "Accept-Language header"
}
```

---

## 2. Get Supported Languages

### Request

```http
GET /api/languages
```

### Response

```json
{
    "success": true,
    "languages": [
        "en",
        "fr",
        "es"
    ]
}
```

---

## 3. Get Translations

### Request

```http
GET /api/translations
```

### Header

```http
Accept-Language: fr
```

### Response

```json
{
    "success": true,
    "locale": "fr",
    "translations": {
        "title": "Démonstration Browser Locale",
        "welcome": "Bienvenue dans le projet Laravel Browser Locale",
        "description": "L’application détecte automatiquement la langue du navigateur."
    }
}
```

---

# Postman Testing

## English

Header:

```http
Accept-Language: en
```

Request:

```http
GET /api/translations
```

---

## French

Header:

```http
Accept-Language: fr
```

Request:

```http
GET /api/translations
```

---

## Spanish

Header:

```http
Accept-Language: es
```

Request:

```http
GET /api/translations
```

---

# Example Flow

## 1. Check Current Locale

Request:

```http
GET /api/locale
```

Header:

```http
Accept-Language: en
```

Response:

```json
{
    "success": true,
    "locale": "en",
    "detected_from": "Accept-Language header"
}
```

---

## 2. Request French Translation

Request:

```http
GET /api/translations
```

Header:

```http
Accept-Language: fr
```

Response:

```json
{
    "success": true,
    "locale": "fr",
    "translations": {
        "title": "Démonstration Browser Locale",
        "welcome": "Bienvenue dans le projet Laravel Browser Locale",
        "description": "L’application détecte automatiquement la langue du navigateur."
    }
}
```

---

## 3. Request Spanish Translation

Request:

```http
GET /api/translations
```

Header:

```http
Accept-Language: es
```

Response:

```json
{
    "success": true,
    "locale": "es",
    "translations": {
        "title": "Demostración Browser Locale",
        "welcome": "Bienvenido al proyecto Laravel Browser Locale",
        "description": "La aplicación detecta automáticamente el idioma del navegador."
    }
}
```

---

## Screenshots

### Detect Browser Locale

<img width="1375" height="992" alt="Screenshot 2026-06-22 105817" src="https://github.com/user-attachments/assets/d163201f-404c-4375-a0dd-a6d0ab8ba697" />

### Supported Languages

<img width="1377" height="996" alt="Screenshot 2026-06-22 105836" src="https://github.com/user-attachments/assets/c0e02c58-1130-467b-bca6-1bab552a495d" />

### English Translation Response

<img width="1383" height="995" alt="Screenshot 2026-06-22 105908" src="https://github.com/user-attachments/assets/9338bd43-418c-401e-8dfd-4ac98f4c5c28" />

### French Translation Response

<img width="1385" height="1000" alt="Screenshot 2026-06-22 105929" src="https://github.com/user-attachments/assets/7cb5b331-b2d5-4e9a-bcad-4e5609a6a264" />

### Spanish Translation Response

<img width="1386" height="998" alt="Screenshot 2026-06-22 105948" src="https://github.com/user-attachments/assets/ef55e6b5-3db6-423c-b878-2faa82d3eca8" />

---

## Project Structure

```text
PHP_Laravel12_Browser_Locale_With_Api
│
├── app
│   └── Http
│       ├── Controllers
│       │   └── LocaleApiController.php
│       │
│       └── Middleware
│           └── BrowserLocaleMiddleware.php
│
├── bootstrap
│   └── app.php
│
├── lang
│   ├── en
│   │   └── messages.php
│   │
│   ├── fr
│   │   └── messages.php
│   │
│   └── es
│       └── messages.php
│
├── routes
│   └── api.php
│
├── .env
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

## Conclusion

PHP_Laravel12_Browser_Locale_With_Api demonstrates how Laravel 12 can provide multilingual API responses by leveraging middleware, localization files, and the Accept-Language header to dynamically serve content in the client's preferred language while maintaining a clean and scalable RESTful architecture.
