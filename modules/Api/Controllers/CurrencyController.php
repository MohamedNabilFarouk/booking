<?php
namespace Modules\Api\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hotel\Models\Hotel;

class CurrencyController extends Controller
{
    public function getCurrency(){
        $actives = \App\Currency::getActiveCurrency();
        $current = \App\Currency::getCurrent('currency_main');
        return $this->sendSuccess(
            [
                'current'=>$current,
                'allCurrency'=>$actives,
            ]
        );
    }


    public function inputSearch(Request $request){
        $hotels = Hotel::where([
            ['title', 'LIKE', '%' . $request->title . '%'],
            ['status', '!=', 'pending'],
            ['deleted_at', '=', null]
            ])->get();
        return $this->sendSuccess([
            'hotels'=> $hotels
        ]);
    }
}
