@extends('layouts.app')
{{-- @dd($package->price) --}}
@section('head')
    <link href="{{ asset('module/booking/css/checkout.css?_ver='.config('app.version')) }}" rel="stylesheet">
@endsection
@section('content')
    <div class="bravo-booking-page padding-content" >
        <div class="container">
            <div id="bravo-checkout-page" >
                <div class="row">
                  
                    <div class="col-md-8">
                        <h3 class="form-title">{{__('Booking Submission')}}</h3>
                         <div class="booking-form">
                            @include('admin.message')
                            {{-- <form action='{{route('hotel.vendor.room.bookRoomPackage')}}' method='post' enctype="multipart/form-data" class="input-has-icon"> --}}
                                @php
                                $gateway = App\PaymentGateway::where('status','1')->first();
                            
                            @endphp
                            @if(isset($gateway)&&($gateway->name =='PayMob'))
                                    <form action='{{url('payMobPayment')}}' method='get'>
                                @elseif(isset($gateway)&&($gateway->name =='Fawry'))
                                <form action='{{url('fawryPayment')}}' method='get'>
                                    @else 
                                    <form action='{{route('hotel.vendor.room.bookRoomPackage')}}' method='post' enctype="multipart/form-data" class="input-has-icon">
                                @endif
                                @csrf
                                <input type="hidden" name='package_id' value='{{$package->id}}'>
                                <input type="hidden" name='total' value ='{{Session::get("pack_price_".$package->id)}}'>

                            
                                <div class="row">
                                    <div class="col-md-6">
                                      
                                        <div class="form-group">
                                            <label>{{__("First Name")}}</label>
                                            <div class="upload-btn-wrapper">
                                                <div class="input-group">
                                                    <input type="text"  name='first_name'  class="form-control text-view"  value="{{ old('first_name')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                      
                                        <div class="form-group">
                                            <label>{{__("last Name")}}</label>
                                            <div class="upload-btn-wrapper">
                                                <div class="input-group">
                                                    <input type="text"  name='last_name'  class="form-control text-view"  value="{{ old('last_name')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            
                                    <div class="col-md-6">
                                      
                                        <div class="form-group">
                                            <label>{{__("Email")}}</label>
                                            <div class="upload-btn-wrapper">
                                                <div class="input-group">
                                                    <input type="email"  name='email'  class="form-control text-view"  value="{{ old('email')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                            
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("Phone")}}</label>
                                            <div class="upload-btn-wrapper">
                                                <div class="input-group">
                                                    <input type="text"  name='phone'  class="form-control text-view"  value="{{ old('phone')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("Check In")}}</label>
                                            <div class="upload-btn-wrapper">
                                                <div class="input-group">
                                                    <input type='date' name='from' placeholder="{{__('Check In')}}" required  class="form-control text-view"  value="{{ old('from')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("Check Out")}}</label>
                                            <div class="upload-btn-wrapper">
                                                <div class="input-group">
                                                    <input type='date' name='to' placeholder="{{__('Check out')}}" required  class="form-control text-view"  value="{{ old('to')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    {{-- <div class="col-md-12">
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
                                    </div> --}}
                                   
                                    <div class="col-md-12">
                                        <hr>
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{__('Pay and Book')}}</button>
                                        {{-- <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{__('Pay and Book')}}</button> --}}
                                        
                                        
                                    </div>
                                </div>
                            </form>

                         </div>
                    </div>
                    <div class="col-md-4">
                        <div class="booking-detail booking-form">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="mb-0"><label >
                                            {{format_money(Session::get("pack_price_".$package->id))}}
                                         
                                            </label></h4>
                                        {{-- <span class="price"><strong>{{format_money(Session::get("pack_price_".$package->id))}}</strong></span> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection