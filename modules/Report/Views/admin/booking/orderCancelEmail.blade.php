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
                    $body .= "<h4 style='font-size: 20px;font-style: normal;font-weight: normal; margin: 15px 0'>Sorry ". $booking->customer ? $booking->customer->name : " " ."!</h4>";
                    $body .= "<h4 style='font-size: 20px;font-style: normal;font-weight: normal; margin: 15px 0'>Your booking in ". $booking->service['title'] ." is Canceled</h2>";
                        $body .= "<img src='" . FileHelper::url($booking->service['image_id'], 'full') . "' style='margin: 15px auto; max-width: 100%;height: 360px;' />";

                        $body .= "<h4 style='font-size: 20px;font-style: normal;font-weight: normal; margin: 15px 0'>You can book in one of the following hotels</h4>";
                        foreach ($hotels as $h)
                        {
                            $hotel = \Modules\Hotel\Models\Hotel::find($h);
                            if ($hotel){
                                $body .= "<img src='" . FileHelper::url($hotel['image_id'], 'full') . "' style='margin: 15px auto; max-width: 100%;height: 360px;' />";
                                $body .= "<a href='".url(app_get_locale(false, false, '/') . config('hotel.hotel_route_prefix') . "/". $hotel->slug)."'>" . $hotel->title . "</a><br>";
                            }
                        }
                        $body .= "<strong>Thanks,</strong><br><a href='".route('home')."'> Hoteels Egypt</a>";
                        $body .= "</div>";
                        echo $body;
            @endphp
        </div>
    </div>
@endsection
