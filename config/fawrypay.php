<?php

return [

    /*
    |--------------------------------------------------------------------------
    | FawryPay Enviroment
    |--------------------------------------------------------------------------
    |
    | should be on of this ('TEST','LIVE').
    |
    */

    'enviroment' => 'LIVE',

    /*
    |--------------------------------------------------------------------------
    | FawryPay Credentials (Test)
    |--------------------------------------------------------------------------
    |
    | use your fawrypay test credentials
    |
    */
    'merchant_code_test' => '1tSa6uxz2nRJRdVmo0mNVw==',
    'secure_key_test' => 'e6fd80887c4941cda6f7488d59e79878',

    /*
    |--------------------------------------------------------------------------
    | FawryPay Credentials (LIVE)
    |--------------------------------------------------------------------------
    |
    | use your fawrypay live credentials
    |
    */
    'merchant_code' => 'rihWxTOJDzVt5k9/Wv8ueQ==',
    'secure_key' => 'b5b861161fea46359d231ad07b67d16f',
    
    /*
    |--------------------------------------------------------------------------
    | FawryPay Expiry
    |--------------------------------------------------------------------------
    |
    | use 1 for one hour, 24 for one day, 72 for 3 days and etc
    |
    */
    'expiry_in_hours' => '72',
];
