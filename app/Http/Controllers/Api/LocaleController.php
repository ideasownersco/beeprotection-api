<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Cache;
use Illuminate\Http\Request;

class LocaleController extends Controller
{

    public function setLocale(Request $request)
    {
        $locale = $request->lang;
        if (in_array($locale, ['en', 'ar'])) {
            if (app()->getLocale() !== $locale) {
                session()->put('locale', $locale);
                Cache::forget('countries');
                session()->forget('selectedCountry');
            }
        }

        return redirect()->back();
    }

}
