
<form action="{{ route("hotel.search") }}" class="form bravo_form" method="get">
    <div class="g-field-search">
        <div class="row">
            @php $hotel_search_fields = setting_item_array('hotel_search_fields');
            $hotel_search_fields = array_values(\Illuminate\Support\Arr::sort($hotel_search_fields, function ($value) {
                return $value['position'] ?? 0;
            }));

            /* $hotel_search_fields['3']=['title'=>'Check-out','field'=>'date2','size'=>'3','position'=>'3'];*/

      /*dd($hotel_search_fields); */
            @endphp
            @if(!empty($hotel_search_fields))
                @foreach($hotel_search_fields as $field)

{{--                    {{dd($field)}}--}}
                    @php $field['title'] = $field['title_'.app()->getLocale()] ?? $field['title'] ?? "" @endphp
                    <div class="col-md-{{ $field['size'] ?? "6" }} border-right">
{{--                    <div class="col-md-3 border-right">--}}
                        @switch($field['field'])
                            @case ('service_name')
                                @include('Hotel::frontend.layouts.search.fields.service_name')
                            @break
                            @case ('location')
                                @include('Hotel::frontend.layouts.search.fields.location')
                            @break
                            @case ('date')
                                @include('Hotel::frontend.layouts.search.fields.date')
                            @break
                            @case ('guests')
                                @include('Hotel::frontend.layouts.search.fields.guests')
                            @break
                        @endswitch
                    </div>

                @endforeach
            @endif
        </div>
    </div>
    <div class="g-button-submit">
        <button class="btn btn-primary btn-search" id='done' type="submit">{{__("Search")}}</button>
    </div>
</form>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
// $(function()){

$("#done").click(function(){
    var start;
    var end;
     var date;

// $("#start").change(function(){
    start = $('#start').val();
    //  alert(start);
//    });
//    $("#end").change(function(){
    end = $('#end').val();
    // alert(end);
//    });
    // alert(start);
    date = start +' - '+ end;
    // alert(date);
   $('#date-val').val(date);
//    alert(date);
   });

// }
</script>
