<?php

namespace App\Http\Controllers;

use App\Models\LocaleHistory;
use Illuminate\Support\Facades\App;

class LocaleApiController extends Controller
{
    protected $supportedLocales = [
        'en',
        'fr',
        'es'
    ];

    /**
     * Get current locale
     */
    public function currentLocale()
    {
        return response()->json([
            'success' => true,
            'locale' => App::getLocale(),
            'detected_from' => 'Accept-Language header'
        ]);
    }

    /**
     * Get supported languages
     */
    public function languages()
    {
        return response()->json([
            'success' => true,
            'languages' => $this->supportedLocales
        ]);
    }

    /**
     * Get translated content
     */
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

    /**
     * Locale statistics
     */
    public function statistics()
    {
        return response()->json([
            'success' => true,
            'total_requests' => LocaleHistory::count(),
            'english_requests' => LocaleHistory::where('locale', 'en')->count(),
            'french_requests' => LocaleHistory::where('locale', 'fr')->count(),
            'spanish_requests' => LocaleHistory::where('locale', 'es')->count(),
        ]);
    }

    /**
     * Locale request history
     */
    public function history()
    {
        return response()->json([
            'success' => true,
            'data' => LocaleHistory::oldest()->paginate(3)
        ]);
    }

    /**
     * Search language
     */
    public function searchLanguage($keyword)
    {
        $languages = collect($this->supportedLocales)
            ->filter(function ($language) use ($keyword) {
                return str_contains(
                    strtolower($language),
                    strtolower($keyword)
                );
            })
            ->values();

        return response()->json([
            'success' => true,
            'search' => $keyword,
            'results' => $languages
        ]);
    }
}