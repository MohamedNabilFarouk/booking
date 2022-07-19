<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{$html_class ?? ''}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php event(new \Modules\Layout\Events\LayoutBeginHead()); @endphp
    @php
        $favicon = setting_item('site_favicon');
    @endphp
    @if($favicon)
        @php
            $file = (new \Modules\Media\Models\MediaFile())->findById($favicon);
        @endphp
        @if(!empty($file))
            <link rel="icon" type="{{$file['file_type']}}" href="{{asset('uploads/'.$file['file_path'])}}" />
        @else:
            <link rel="icon" type="image/png" href="{{url('images/favicon.png')}}" />
        @endif
    @endif

    @include('Layout::parts.seo-meta')
    <link href="{{ asset('libs/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/css/notification.css') }}" rel="newest stylesheet">
    <link href="{{ asset('dist/frontend/css/app.css?_ver='.config('app.version')) }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset("libs/daterange/daterangepicker.css") }}" >
{{-- data table --}}
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css'>

{{-- end datatable --}}

<!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel='stylesheet' id='google-font-css-css'  href='https://fonts.googleapis.com/css?family=Poppins%3A300%2C400%2C500%2C600' type='text/css' media='all' />
    {!! \App\Helpers\Assets::css() !!}
    {!! \App\Helpers\Assets::js() !!}
    <script>
        var bookingCore = {
            url:'{{url( app_get_locale() )}}',
            url_root:'{{ url('') }}',
            booking_decimals:{{(int)get_current_currency('currency_no_decimal',2)}},
            thousand_separator:'{{get_current_currency('currency_thousand')}}',
            decimal_separator:'{{get_current_currency('currency_decimal')}}',
            currency_position:'{{get_current_currency('currency_format')}}',
            currency_symbol:'{{currency_symbol()}}',
			currency_rate:'{{get_current_currency('rate',1)}}',
            date_format:'{{get_moment_date_format()}}',
            map_provider:'{{setting_item('map_provider')}}',
            map_gmap_key:'{{setting_item('map_gmap_key')}}',
            routes:{
                login:'{{route('auth.login')}}',
                register:'{{route('auth.register')}}',
                checkout:'{{is_api() ? route('api.booking.doCheckout') : route('booking.doCheckout')}}'
            },
            module:{
                hotel:'{{route('hotel.search')}}',
                car:'{{route('car.search')}}',
                tour:'{{route('tour.search')}}',
                space:'{{route('space.search')}}',
            },
            currentUser: {{(int)\Illuminate\Support\Facades\Auth::id()}},
            isAdmin : {{is_admin() ? 1 : 0}},
            rtl: {{ setting_item_with_lang('enable_rtl') ? "1" : "0" }},
            markAsRead:'{{route('core.notification.markAsRead')}}',
            markAllAsRead:'{{route('core.notification.markAllAsRead')}}',
            loadNotify : '{{route('core.notification.loadNotify')}}',
            pusher_api_key : '{{setting_item("pusher_api_key")}}',
            pusher_cluster : '{{setting_item("pusher_cluster")}}',
        };
        var i18n = {
            warning:"{{__("Warning")}}",
            success:"{{__("Success")}}",
        };
        var daterangepickerLocale = {
            "applyLabel": "{{__('Apply')}}",
            "cancelLabel": "{{__('Cancel')}}",
            "fromLabel": "{{__('From')}}",
            "toLabel": "{{__('To')}}",
            "customRangeLabel": "{{__('Custom')}}",
            "weekLabel": "{{__('W')}}",
            "first_day_of_week": {{ setting_item("site_first_day_of_the_weekin_calendar","1") }},
            "daysOfWeek": [
                "{{__('Su')}}",
                "{{__('Mo')}}",
                "{{__('Tu')}}",
                "{{__('We')}}",
                "{{__('Th')}}",
                "{{__('Fr')}}",
                "{{__('Sa')}}"
            ],
            "monthNames": [
                "{{__('January')}}",
                "{{__('February')}}",
                "{{__('March')}}",
                "{{__('April')}}",
                "{{__('May')}}",
                "{{__('June')}}",
                "{{__('July')}}",
                "{{__('August')}}",
                "{{__('September')}}",
                "{{__('October')}}",
                "{{__('November')}}",
                "{{__('December')}}"
            ],
        };
    </script>
    <!-- Styles -->
    @yield('head')
    {{--Custom Style--}}
    <link href="{{ route('core.style.customCss') }}" rel="stylesheet">
    <link href="{{ asset('libs/carousel-2/owl.carousel.css') }}" rel="stylesheet">
{{--    @if(setting_item_with_lang('enable_rtl'))--}}
{{--        <link href="{{ asset('dist/frontend/css/rtl.css') }}" rel="stylesheet">--}}
{{--    @endif--}}
    {!! setting_item('head_scripts') !!}
    {!! setting_item_with_lang_raw('head_scripts') !!}

    @php event(new \Modules\Layout\Events\LayoutEndHead()); @endphp


    <style>
#google_translate_element {position:relative}

#google_translate_element span {display: none!important; }

#google_translate_element:after {
content: "";
  bottom:0;
  left:0;
  width:50%;
  background:#1A2B48;
  position: absolute;
  z-index: 1;
  height: 40%;
}
.goog-te-gadget-icon{
background:none !important;
}

.goog-te-banner-frame.skiptranslate {
    display: none !important;
    }
body {
    top: 0px !important;
    }

    .goog-te-balloon-frame { display: none; }

#google_translate_element select {
padding: 1px;
margin-left: 300px;

}

.goog-te-banner-frame.skiptranslate {display: none!important; visibility: hidden!important;}


.goog-tooltip {
    display: none !important;
}
.goog-tooltip:hover {
    display: none !important;
}

<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-197315748-3">
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-197315748-3');
    </script>
</style>

    @if(app()->getLocale() == "egy")
        <link href="{{ asset('dist/frontend/css/rtl.css') }}" rel="stylesheet">
    @endif
</head>
<body class="frontend-page {{ !empty($row->header_style) ? "header-".$row->header_style : "header-normal" }} {{$body_class ?? ''}} @if(setting_item_with_lang('enable_rtl')) is-rtl @endif @if(is_api()) is_api @endif">
    @php event(new \Modules\Layout\Events\LayoutBeginBody()); @endphp

    {!! setting_item('body_scripts') !!}
    {!! setting_item_with_lang_raw('body_scripts') !!}
    <div class="bravo_wrap">
        @if(!is_api())
            @include('Layout::parts.topbar')
            @include('Layout::parts.header')
        @endif

        <!-- <div id="google_translate_element"></div> -->

        <!-- <iframe scrolling="no" width="100%" height="164" frameborder="0" src="//www.travelpayouts.com/widgets/6b9d437c690a22ee8c917c04bd39e380.html?v=2157"></iframe> -->
        <!-- <iframe scrolling="no" width="100%" height="164" frameborder="0" src="//www.travelpayouts.com/widgets/6b9d437c690a22ee8c917c04bd39e380.html?v=2157"></iframe> -->
        <!-- <iframe scrolling="no" width="100%" height="1000" frameborder="0" src="//www.travelpayouts.com/widgets/6b9d437c690a22ee8c917c04bd39e380.html?v=2216"></iframe> -->
        <!-- <script charset="utf-8" src="//www.travelpayouts.com/widgets/6b9d437c690a22ee8c917c04bd39e380.js?v=2157" async></script> -->
        <!-- <p><iframe src="//www.travelpayouts.com/widgets/6b9d437c690a22ee8c917c04bd39e380.html?v=2216" width="100%" height="1000" frameborder="0" scrolling="no"></iframe></p> -->

        @yield('content')

        @include('Layout::parts.footer')
    </div>
    {!! setting_item('footer_scripts') !!}
    {!! setting_item_with_lang_raw('footer_scripts') !!}
    @php event(new \Modules\Layout\Events\LayoutEndBody()); @endphp


    {{-- data table scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/dataTables.bootstrap.min.js"></script>
{{-- <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.0/moment.min.js'></script> --}}

<script>
// Code By Webdevtrick ( https://webdevtrick.com )
$(document).ready(function() {
   $("#sort").DataTable({
      columnDefs : [
    { type : 'date', targets : [3] }
],
   });
});
</script>
{{-- end data table scripts --}}

<script type="text/javascript">
 function googleTranslateElementInit() {
 new google.translate.TranslateElement({ pageLanguage: "ar" }, "google_translate_element");
 };
</script>
<script src="https://uploads-ssl.webflow.com/5e6cdff43dea835e0d32f309/5e6ce61775d1ae4d0299ac82_translator.txt"></script>

<!-- <script type="text/javascript">
  function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'ar'}, 'google_translate_element');
  }
</script> -->

<!-- <script type="text/javascript"
 src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"> -->

<script>
$(function() {
    // $(".datepicker").datepicker();
    // $('.drp-calendar').addClass('notranslate');
    // $('.date-wrapper').addClass('notranslate');
    $('.check-in-render').addClass('notranslate');
    $('.daterangepicker').addClass('notranslate');
});

</script>
<script>
    {{--  $('.slider-cities .carousel').carousel({
        interval: 5000
    })  --}}
</script>
</body>
</html>
