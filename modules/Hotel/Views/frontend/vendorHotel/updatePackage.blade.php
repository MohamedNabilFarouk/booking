@extends('layouts.user')
@section('head')
{{-- @dd($package->prices) --}}
@endsection
{{-- @dd($user->vendorRequest->getCommercialRecordUrl()) --}}
@section('content')

{{-- <form action='{{ route("user.upgrade_vendor") }}' method='get' enctype="multipart/form-data">
    @csrf
    <input type='file' name='tax_card'>
    <input type='file' name='commercial_record'>
    <button type='submit'>save</button>
</form> --}}

<h2 class="title-bar">
    {{__("Room Package")}}
</h2>
@include('admin.message')
<form action="{{ route("hotel.vendor.room.updatePackage",$package->id) }}" method='post' enctype="multipart/form-data" class="input-has-icon">
    @csrf
    @method('put')
    <input type="hidden" name='room_id' value='{{$package->room_id}}'>

    <div class="row" id="country_price">
        <div class="col-md-6">
            <div class="form-title">
                <strong>{{__("Title")}}</strong>
            </div>
            <div class="form-group">
                {{-- <label>{{__("Tax Card")}}</label> --}}
                <div class="upload-btn-wrapper">
                    <div class="input-group">
                        <input type="text"  name='name'  class="form-control text-view"  value="{{$package->name}}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">

            <div class="form-title">
                <strong>{{__("Price")}}</strong>
            </div>
            <div class="form-group">
                {{-- <label>{{__("Tax Card")}}</label> --}}
                <div class="upload-btn-wrapper">
                    <div class="input-group">
                        <input type="number"  name='price'  class="form-control text-view"  value="{{$package->price}}">
                    </div>
                </div>
            </div>
           
        </div>
       

        <div class="col-md-12">
            <div class="form-title">
                <strong>{{__("Description")}}</strong>
            </div>
            <div class="form-group">
                {{-- <label>{{__("Tax Card")}}</label> --}}
                <div class="upload-btn-wrapper">
                    <div class="input-group">
                        <input type="text"  name='des'  class="form-control text-view"  value="{{$package->des}}">
                    </div>
                </div>
            </div>
        </div>

        @foreach($package->prices as $index => $p)
        <div class="col-md-6">
            <div class="form-title">
                <strong>{{__("Country")}}</strong>
            </div>
            <div class="form-group">
                {{-- <label>{{__("Tax Card")}}</label> --}}
                <div class="upload-btn-wrapper">
                    <div class="input-group">
                        <input type="text"  name='arr[{{$index}}][country]'  class="form-control text-view"  value="{{$p->ip}}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-title">
                <strong>{{__("Price")}}</strong>
            </div>
            <div class="form-group">
                {{-- <label>{{__("Tax Card")}}</label> --}}
                <div class="upload-btn-wrapper">
                    <div class="input-group">
                        <input type="number"  name='arr[{{$index}}][price]'  class="form-control text-view"  value="{{$p->price}}">
                    </div>
                </div>
            </div>
        </div>
        <script>
             var i = {{$index + 1}};
        </script>
        @endforeach
  

        

        {{-- <hr> --}}
       
       
       
    </div>
   
    <div class='row'>
        <div class="col-md-12">
            <a class='btn btn-danger'  id='add'>+</a>
       </div>
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-title"><strong>{{__('Attributes')}}</strong></div>
                <div class="panel-body">
                    <div class="terms-scrollable">
                        @php
                            $c= 0;    
                            $t_count =count($package->packageTerms);
                        @endphp
                        @foreach($terms as $term)
                            <label class="term-item">
                                @if($t_count > 0)
                                <input @if($package->packageTerms[$c]->term_id == $term->id) checked @endif type="checkbox" name="terms[]" value="{{$term->id}}">
                                @else
                                <input @if(!empty($selected_terms) and $selected_terms->contains($term->id)) checked @endif type="checkbox" name="terms[]" value="{{$term->id}}">
                                @endif
                                <span class="term-name">{{$term->name}}</span>
                            </label>
                            <?php 
                            if($c < $t_count - 1){ $c=$c + 1 ; }
                            ?> 
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
     
       
        <div class="col-md-12">
            <hr>
            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{__('Save Changes')}}</button>
        </div>
    </div>
</form>
@endsection
@section('footer')
<script>
//    alert(i);
   $('#add').click(function(){
    //    alert('here');
    
    // event.preventDefault();

    //    alert('here');
       var input = '<div class="col-md-6"><div class="form-title"><strong>{{__("Countries")}}</strong></div><div class="form-group"><div class="upload-btn-wrapper"><div class="input-group"><input type="text" name="arr['+i+'][country]" class="form-control text-view" required></div></div></div></div><div class="col-md-6"><div class="form-title"><strong>{{__("Price")}}</strong></div><div class="form-group"><div class="upload-btn-wrapper"><div class="input-group"><input type="number" name="arr['+i+'][price]" class="form-control text-view" required></div></div></div></div>';
       $('#country_price').append(input);
       i++;
   }) ;
</script>
@endsection