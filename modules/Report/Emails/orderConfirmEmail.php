<?php

    namespace Modules\Report\Emails;

    use App\User;
    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;
    use Modules\Media\Helpers\FileHelper;

    class orderConfirmEmail extends Mailable
    {
        use Queueable, SerializesModels;


        public $booking;

        public function __construct($booking)
        {
            $this->booking = $booking;
        }

        public function build()
        {
            $subject = "Your booking in ". $this->booking->service['title'] ." is Confirmed";
            return $this->subject($subject)->view('Report::admin.booking.orderConfirmEmail', compact($this->booking));
        }
    }
