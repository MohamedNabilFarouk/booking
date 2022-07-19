@extends('Email::layout')
@section('content')
    <div class="b-container">
        <div class="b-panel">
            @php
            use Modules\Media\Helpers\FileHelper;
            $logo = asset('/uploads/0000/1/2021/09/30/hoteels-150.png');
            //$logo = "https://testhoteels.hoteelsegypt.com/uploads/0000/1/2021/09/30/hoteels-150.png";
            $body = "<div style='text-align: center'>";
            //<img src='".$logo."' style='margin: 15px auto' alt='Hoteels Egypt'/>"
                $body .= "<h4 style='font-size: 20px;font-style: normal;font-weight: normal; margin: 15px 0'>Thanks ". $booking->customer ? $booking->customer->name : " " ." for your trust!</h4>";
                $body .= "<h4 style='font-size: 20px;font-style: normal;font-weight: normal; margin: 15px 0'>Your booking in ". $booking->service['title'] ." is Confirmed</h2>";
                    $body .= "<h4 style='font-size: 20px;font-style: normal;font-weight: normal; margin: 15px 0'>We hope you will enjoy your reservation.</h4>";
                    $body .= "<img src='" . FileHelper::url($booking->service['image_id'], 'full') . "' style='margin: 15px auto; max-width: 100%;height: 360px;' /> ";

                    $body .= "<h4 style='font-size: 20px;font-style: normal;font-weight: normal; margin: 15px 0'>Here’s your ";

                        $body .= "<a href='".route('booking.code',[$booking->code])."'> receipt</a>.</h4>";

                    $body .= "<strong>Thanks,</strong><br><a href='".route('home')."'> Hoteels Egypt</a>";
                    $body .= "</div>";
                    echo $body;
                @endphp
        </div>
    </div>
@endsection
