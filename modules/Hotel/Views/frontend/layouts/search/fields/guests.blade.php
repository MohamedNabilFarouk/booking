<div class="form-select-guests">
    <div class="form-group">
        <i class="field-icon icofont-travelling"></i>
        <div class="form-content dropdown-toggle" data-toggle="dropdown">
            <div class="wrapper-more">
                <label> {{ $field['title'] }} </label>
                @php
                    $adults = request()->query('adults',1);
                    $children = request()->query('children',0);
                @endphp
                <div class="render">
                    <span class="adults" ><span class="one @if($adults >1) d-none @endif">{{__('1 Adult')}}</span> <span class="@if($adults <= 1) d-none @endif multi" data-html="{{__(':count Adults')}}">{{__(':count Adults',['count'=>request()->query('adults',1)])}}</span></span>
                    -
                    <span class="children" >
                            <span class="one @if($children >1) d-none @endif" data-html="{{__(':count Child')}}">{{__(':count Child',['count'=>request()->query('children',0)])}}</span>
                            <span class="multi @if($children <=1) d-none @endif" data-html="{{__(':count Children')}}">{{__(':count Children',['count'=>request()->query('children',0)])}}</span>
                        </span>
                </div>
            </div>
        </div>
        <div class="dropdown-menu select-guests-dropdown" >
            <!-- <div class="dropdown-item-row">
                <div class="label">{{__('Rooms')}}</div>
                <div class="val">
                    <span class="btn-minus" data-input="room"><i class="icon ion-md-remove"></i></span>
                    <span class="count-display"><input type="number" name="room" value="{{request()->query('room',1)}}" min="1"></span>
                    <span class="btn-add" data-input="room"><i class="icon ion-ios-add"></i></span>
                </div>
            </div> -->
            <div class="dropdown-item-row">
                <div class="label">{{__('Adults')}}</div>
                <div class="val">
                    <span class="btn-minus" data-input="adults"><i class="icon ion-md-remove"></i></span>
                    <span class="count-display"><input type="number" name="adults" value="{{request()->query('adults',1)}}" min="1"></span>
                    <span class="btn-add" data-input="adults"><i class="icon ion-ios-add"></i></span>
                </div>
            </div>
            <div class="dropdown-item-row">
                <div class="label">{{__('Children')}}</div>
                <div class="val">
                    <span class="btn-minus" id='sub' data-input="children"><i class="icon ion-md-remove"></i></span>
                    <span class="count-display"><input type="number" id='ch_no' name="children" readonly value="{{request()->query('children',0)}}" min="0"></span>
                    <span class="btn-add" id='add' data-input="children"><i class="icon ion-ios-add"></i></span>
                </div>
            </div>
<div id='ages'>
<div>
          
            </div>
</div>


                
            </div>
        </div>
    </div>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
var child = $('#ch_no').val();
if(child == 0){
    var c = 1;
} else{
    var c = parseInt(child) + 1 ;
} 

  
 $("#add").click(function(){
    var data =' <div class="dropdown-item-row"> <div class="label">{{__("Child")}} '+c+'</div> <div class="val"> <span class="count-display"><input type="number" name="age[]" min="1" max="11"></span> </div> </div>'
        $('#ages').append(data);
   c++;
  });

  $("#sub").click(function(){
    $('#ages > div').last().remove();
    if(c > 0){
        c--;
    }

});
</script>