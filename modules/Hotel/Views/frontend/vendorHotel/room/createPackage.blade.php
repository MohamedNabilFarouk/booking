@extends('layouts.user')
@section('head')

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
<form action="{{ route("hotel.vendor.room.storePackage") }}" method='post' enctype="multipart/form-data" class="input-has-icon">
    @csrf
    <input type="hidden" name='room_id' value='{{$id}}'>

    <div class="row" id="country_price">
        <div class="col-md-6">
            <div class="form-title">
                <strong>{{__("Title")}}</strong>
            </div>
            <div class="form-group">
                {{-- <label>{{__("Tax Card")}}</label> --}}
                <div class="upload-btn-wrapper">
                    <div class="input-group">
                        <input type="text"  name='name'  class="form-control text-view"  value="{{ old('name')}}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-title">
                <strong>{{__("Default Price")}}</strong>
            </div>
            <div class="form-group">
                {{-- <label>{{__("Tax Card")}}</label> --}}
                <div class="upload-btn-wrapper">
                    <div class="input-group">
                        <input type="number" name="price"  class="form-control text-view"  value="{{ old('price')}}" required>
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
                        <input type="text"  name='des'  class="form-control text-view"  value="{{ old('des')}}">
                    </div>
                </div>
            </div>
        </div>

      
       
    </div> {{--end of row--}}
    <div class='row'>
        <div class="col-md-3">
            <a class='btn btn-danger'  id='add'>+</a>
       </div>

        <div class="col-md-12">
           
            <div class="panel">
                <div class="panel-title"><strong>{{__('Attributes')}}</strong></div>
                <div class="panel-body">
                    <div class="terms-scrollable">
                        @foreach($terms as $term)
                            <label class="term-item">
                                <input @if(!empty($selected_terms) and $selected_terms->contains($term->id)) checked @endif type="checkbox" name="terms[]" value="{{$term->id}}">
                                <span class="term-name">{{$term->name}}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
       
        <div class="col-md-12">
            <hr>
            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{__('Save Changes')}}</button>
        </div>
   </div> {{-- end of row --}}
</form>
@endsection
@section('footer')
<script>
    var i = 1;
   $('#add').click(function(){
    //    alert('here');
    
    // event.preventDefault();

    //    alert('here');
       var input = '<div class="col-md-6"><div class="form-title"><strong>{{__("Countries")}}</strong></div><div class="form-group"><div class="upload-btn-wrapper"><div class="input-group"><input type="text" name="arr['+i+'][country]" class="form-control text-view" required></div></div></div></div><div class="col-md-3"><div class="form-title"><strong>{{__("Price")}}</strong></div><div class="form-group"><div class="upload-btn-wrapper"><div class="input-group"><input type="number" name="arr['+i+'][price]" class="form-control text-view" required></div></div></div></div>';
       $('#country_price').append(input);
       i++;
   }) ;
</script>
@endsection