<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $page_title ?? 'Dashboard'}} - {{setting_item('site_title') ?? 'Hoteels Egypt'}}</title>

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

    <meta name="robots" content="noindex, nofollow" />
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/flags/css/flag-icon.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{url('libs/daterange/daterangepicker.css')}}"/>
    <link href="{{ asset('dist/admin/css/vendors.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/admin/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/admin/css/app.css') }}" rel="stylesheet">
    {!! \App\Helpers\Assets::css() !!}
    {!! \App\Helpers\Assets::js() !!}
    <script>
        var bookingCore  = {
            url:'{{url('/')}}',
            map_provider:'{{setting_item('map_provider')}}',
            map_gmap_key:'{{setting_item('map_gmap_key')}}',
            csrf:'{{csrf_token()}}',
            date_format:'{{get_moment_date_format()}}',
            markAsRead:'{{route('core.admin.notification.markAsRead')}}',
            markAllAsRead:'{{route('core.admin.notification.markAllAsRead')}}',
            loadNotify : '{{route('core.admin.notification.loadNotify')}}',
            pusher_api_key : '{{setting_item("pusher_api_key")}}',
            pusher_cluster : '{{setting_item("pusher_cluster")}}',
            isAdmin : {{is_admin() ? 1 : 0}},
            currentUser: {{(int)Auth::id()}},
        };
        var i18n = {
            warning:"{{__("Warning")}}",
            success:"{{__("Success")}}",
            confirm_delete:"{{__("Do you want to delete?")}}",
            confirm_recovery:"{{__("Do you want to restore?")}}",
            confirm:"{{__("Confirm")}}",
            cancel:"{{__("Cancel")}}",
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
    <script src="{{ asset('libs/tinymce/js/tinymce/tinymce.min.js') }}" ></script>
    @yield('script.head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

</head>
<body class="{{($enable_multi_lang ?? '') ? 'enable_multi_lang' : '' }} @if(setting_item('site_enable_multi_lang')) site_enable_multi_lang @endif">
<div id="app">
    <div class="main-header d-flex">
        @include('Layout::admin.parts.header')
    </div>
    <div class="main-sidebar">
        @include('Layout::admin.parts.sidebar')
    </div>
    <div class="main-content">
        @include('Layout::admin.parts.bc')
        @yield('content')
        <footer class="main-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 copy-right" >
                        {{date('Y')}} &copy; SPC
                    </div>
                    <div class="col-md-6">
                        <div class="text-md-right footer-links d-none d-sm-block">
                            <a href="{{__('https://spctec.com/')}}" target="_blank">{{__('About Us')}}</a>
                            <a href="{{__('https://m.me/spctec')}}" target="_blank">{{__('Contact Us')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <div class="backdrop-sidebar-mobile"></div>
</div>

@include('Media::browser')

<!-- Scripts -->
{!! \App\Helpers\Assets::css(true) !!}
<script src="{{ asset('libs/pusher.min.js') }}"></script>
<script src="{{ asset('dist/admin/js/manifest.js?_ver='.config('app.version')) }}" ></script>
<script src="{{ asset('dist/admin/js/vendor.js?_ver='.config('app.version')) }}" ></script>


<script src="{{ asset('dist/admin/js/app.js?_ver='.config('app.version')) }}" ></script>
<script src="{{ asset('libs/vue/vue'.(!env('APP_DEBUG') ? '.min':'').'.js') }}"></script>

<script src="{{ asset('libs/select2/js/select2.min.js') }}" ></script>
<script src="{{ asset('libs/bootbox/bootbox.min.js') }}"></script>

<script src="{{url('libs/daterange/moment.min.js')}}"></script>
<script src="{{url('libs/daterange/daterangepicker.min.js?_ver='.config('app.version'))}}"></script>

{!! \App\Helpers\Assets::js(true) !!}

@yield('script.body')

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>--}}
{{--<script src="http://code.jquery.com/jquery-1.7.min.js" type="text/javascript" ></script>--}}
<!--<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>-->

<script src="{{ asset('dist/admin/js/jquery.dataTables.min.js') }}" type="text/javascript" ></script>
<script>
    // $.noConflict();
    jQuery( document ).ready(function( $ ) {
        $('table').DataTable({
            "ordering": false,
            "lengthChange": false
        });
    });
    // Code that uses other library's $ can follow here.
</script>

</body>
</html>
