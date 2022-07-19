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
    {{__("Vendor Information")}}
</h2>
@include('admin.message')
<form action="{{ route('user.upgrade_vendor') }}" method='get' enctype="multipart/form-data" class="input-has-icon">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-title">
                <strong>{{__("Tax Card")}}</strong>
            </div>
            <div class="form-group">
                {{-- <label>{{__("Tax Card")}}</label> --}}
                <div class="upload-btn-wrapper">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-default btn-file">
                                {{__("Browse")}}… <input type="file">
                            </span>
                        </span>
                        <input type="text" data-error="{{__("Error upload...")}}" data-loading="{{__("Loading...")}}" class="form-control text-view" readonly value="{{ get_file_url( old('tax_card') )}}">
                    </div>
                    <input type="hidden" class="form-control" name="tax_card" value="{{ old('tax_card')}}" >
                            <img class="image-demo" src="{{ get_file_url( old('tax_card')) }}"/> 
                </div>
            </div>
        </div>

      
        
        <div class="col-md-6">
            <div class="form-title">
                <strong>{{__("Commercial Record")}}</strong>
            </div>
            <div class="form-group">
                {{-- <label>{{__("commercial_record")}}</label> --}}
                <div class="upload-btn-wrapper">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-default btn-file">
                                {{__("Browse")}}… <input type="file">
                            </span>
                        </span>
                        <input type="text" data-error="{{__("Error upload...")}}" data-loading="{{__("Loading...")}}" class="form-control text-view" readonly value="{{ get_file_url( old('commercial_record'))}}">
                    </div>
                    <input type="hidden" class="form-control" name="commercial_record" value="{{ old('commercial_record')}}" >
                            <img class="image-demo" src="{{ get_file_url( old('commercial_record')) }}"/> 
                </div>
            </div>


         
        </div>
        <div class="col-md-6">
            <div class="form-title">
                    <strong>{{__("Bank Account")}}</strong>
                </div>
                <div class="form-group">
                    
                    <div class="upload-btn-wrapper">
                        <div class="input-group">
                        <input type="text" class="form-control" name="bank_account" value="{{ old('bank_account')}}" >

                        </div>
                    </div>
                </div>
        </div>
        <div class="col-md-6">
            <div class="form-title">
                    <strong>{{__("Bank Name")}}</strong>
                </div>
                <div class="form-group">
                    
                    <div class="upload-btn-wrapper">
                        <div class="input-group">
                        <input type="text" class="form-control" name="bank_name" value="{{ old('bank_name')}}" >

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

@endsection