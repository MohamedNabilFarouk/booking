<!-- <div class="form-group">
    <i class="field-icon icofont-wall-clock"></i>
    <div class="form-content">
        <div class="form-date-search">
            <div class="date-wrapper">
                <div class="check-in-wrapper">
                    <label>{{ $field['title'] ?? "" }}</label>
                    <div class="render check-in-render">{{Request::query('start',display_date(strtotime("today")))}}</div>
                    <span> - </span>
                    <div class="render check-out-render">{{Request::query('end',display_date(strtotime("+1 day")))}}</div>
                </div>
            </div>
            <input type="hidden" class="check-in-input" value="{{Request::query('start',display_date(strtotime("today")))}}" name="start">
            <input type="hidden" class="check-out-input" value="{{Request::query('end',display_date(strtotime("+1 day")))}}" name="end">
            <input type="text" class="check-in-out" name="date" value="{{Request::query('date',date("Y-m-d")." - ".date("Y-m-d",strtotime("+1 day")))}}">
        </div>
    </div>
</div> -->

<div class="form-group">
    <i class="field-icon icofont-wall-clock"></i>
    <div class="form-content">
        <div class="form-date-search-hotel">
            <div class="date-wrapper">
                <div class="check-in-wrapper">
                    <label>{{ $field['title'] }}</label>
                    
                    <!-- <div class="render check-in-render">{{Request::query('start',display_date(strtotime("today")))}}</div> -->
                    <!-- <span> - </span> -->
             
                    <!-- <div class="render check-out-render">{{Request::query('end',display_date(strtotime("+1 day")))}}</div>  -->
             
                </div>
            </div>
            @if($field['position']==2)
            <!-- <input type="date" class="check-in-input form-control"  value="{{Request::query('start',display_date(strtotime("today")))}}" name="start"> -->
            <input type="date" class="check-in-input form-control" id='start1'  value="{{Request::query('start',display_date(strtotime("today")))}}" name="start">
           
            @else
            <!-- <input type="date" class="check-out-input form-control" value="{{Request::query('end',display_date(strtotime('+1 day')))}}" name="end"> -->
            <input type="date" class="check-out-input form-control"  value="{{Request::query('end',display_date(strtotime('+1 day')))}}"  id='end1' name="end">
            <input type="hidden" class="form-control" name="date" id='date-val1'>
            @endif
            
            
        </div>
    </div>
</div>

<!-- start=2021-07-15 -->

