<?php

namespace Modules\Report\Emails;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Media\Helpers\FileHelper;

class orderCancelEmail extends Mailable
{
    use Queueable, SerializesModels;


    public $booking;
    public $hotels;
    public function __construct($booking, $hotels)
    {
        $this->booking = $booking;
        $this->hotels = $hotels;
    }


    public function build()
    {
        $subject = "Your booking in ". $this->booking->service['title'] ." is canceled";
        $hotels = $this->hotels;
        return $this->subject($subject)->view('Report::admin.booking.orderCancelEmail', compact($this->booking, 'hotels'));
    }
}
