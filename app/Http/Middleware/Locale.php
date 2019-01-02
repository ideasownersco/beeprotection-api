<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Locale
{
    public function handle($request, Closure $next)
    {
//        if($request->device_id) {
//            $deviceID = $request->device_id;
//            if(session()->has($deviceID)) {
//                $currentLang = session()->get($deviceID);
//                if($currentLang !== $request->lang) {
//                    session()->put($deviceID,$request->lang);
//                    app()->setLocale($request->lang);
//                }
//            }
//        }
        $lang = $request->lang ? $request->lang : 'en';
        app()->setLocale($lang);

        return $next($request);
    }
}
