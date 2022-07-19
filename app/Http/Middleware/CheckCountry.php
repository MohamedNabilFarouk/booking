<?php

namespace App\Http\Middleware;

use App\Countries;
use App\Currency;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stevebauman\Location\Facades\Location as Locations;

class CheckCountry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
//        $ip = '69.64.52.22'; //test for local us
        // $ip = '66.102.0.0';
         $ip = \request()->ip();  //online
        //  dd($_SERVER);
        $data = Locations::get($ip);
        if (isset($data->countryCode)){
            config()->set('app.country', $data->countryCode);
            $country = Countries::where('code', '=', $data->countryCode)->first();
            $currency = Currency::getCurrent();
            $currencies = Currency::getActiveCurrency();
            if ($country && $country->currency != $currency){
                $activeCur = collect($currencies)->contains('currency_main', $country->currency);
                if ($activeCur){
                    Session::put('bc_current_currency',$country->currency);
                }else{
                    Session::put('bc_current_currency','egp');
                }
            }
        }
        return $next($request);
    }
}
