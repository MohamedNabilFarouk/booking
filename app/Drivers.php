<?php
namespace App\Drivers;

use Illuminate\Support\Fluent;
use Stevebauman\Location\Position;
use Stevebauman\Location\Drivers\Driver;

class MyDriver extends Driver
{
    public function url($ip)
    {
        return "http://driver-url.com?ip=$ip";
    }
    
    protected function process($ip)
    {
        return rescue(function () use ($ip) {
            $response = json_decode(file_get_contents($this->url($ip)), true);
            
            return new Fluent($response);
        }, $rescue = false);
    }

    protected function hydrate(Position $position, Fluent $location)
    {
        $position->countryCode = $location->country_code;

        return $position;
    }
}