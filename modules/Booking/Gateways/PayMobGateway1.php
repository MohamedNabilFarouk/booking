<?php
namespace Modules\Booking\Gateways;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Events\BookingCreatedEvent;

class PayMobGateway1 extends BaseGateway
{
    
    public $name = 'Paymob';
 

    public function getGateway()
    {
        $this->gateway = Omnipay::create('Paymob');
     
    }
    

public function getOptionsConfigs()
    {
        return [
            [
                'type'  => 'checkbox',
                'id'    => 'enable',
                'label' => __('Enable Paymob ?')
            ],
            [
                'type'  => 'input',
                'id'    => 'name',
                'label' => __('Custom Name'),
                'std'   => __("Offline Payment"),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'upload',
                'id'    => 'logo_id',
                'label' => __('Custom Logo'),
            ],
            [
                'type'  => 'textarea',
                'id'    => 'payment_note',
                'label' => __('Payment Note'),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'editor',
                'id'    => 'html',
                'label' => __('Custom HTML Description'),
                'multi_lang' => "1"
            ],
        ];
    }

}