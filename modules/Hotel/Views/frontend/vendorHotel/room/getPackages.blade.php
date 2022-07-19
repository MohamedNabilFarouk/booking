{{-- @dd($packages) --}}
{{-- @dd($packages[0]->room->image_url) --}}
@php


@endphp

@extends('layouts.app')
@section('head')
<style>
    .item-list {
    border: 1px solid #dfdfdf;
    border-radius: 3px;
    overflow: hidden;
    position: relative;
    margin-bottom: 30px;
    }
</style>

@endsection
@section('content')
    <h2 class="title-bar">
       {{__("Packages")}}
       
    </h2>
    @include('admin.message')
    @if(count($packages) > 0)
        <div class="bravo-list-item">
            <div class="bravo-pagination">
                {{-- <span class="count-string">{{ __("Showing :from - :to of :total Hotels",["from"=>$rows->firstItem(),"to"=>$rows->lastItem(),"total"=>$rows->total()]) }}</span> --}}
                {{-- {{$packages->appends(request()->query())->links()}} --}}
            </div>
            <div class="list-item container">
                <div class="row">
                    @foreach($packages as $row)

                        <div class="col-md-12 ">
                          
<div class="item-list">
    <div class="row">
        <div class="col-md-2">
            <div class="thumb-image">
                <a href="" target="_blank">
                    @if(isset($row->room->image_url))
                        <img src="{{$row->room->image_url}}" class="img-responsive" alt="">
                        {{-- $packages[0]->room->image_url --}}
                    @endif
                </a>
            </div>
        </div>
        <div class="col-md-5">
            <div class="item-title">
                <a href="" target="_blank">
                    {{$row->name}}
                </a>
            </div>
          
            <div class="location">
                <i class="icofont-money"></i>
                @php
                 $p = Modules\Hotel\Models\RoomPackagePrices::where([['ip',$packages->countryCode],['room_package_id',$row->id]])->first();   
                 if(isset($p->price)){
                
                 $price = $p->price;
                 }else{
                   $price = $row->price; 
                 
                 }
                 Session::put('pack_price_'.$row->id, $price); 
                 
                @endphp
                {{__("Price")}}: <span class="price">{{format_money($price ?? '' )}}</span>
            </div>
            <div class="location">
                <i class="icofont-settings"></i>
                {{__("Room")}}: <span class="details">{{ $row->room->title }}</span>
            </div>

            <div class="location">
                <i class="icofont-ui-settings"></i>
                {{__("Details")}}: {{$row->des}}
            </div>

            <div class="location">
                <i class="icofont-check-alt"></i>
                    {{__("Features")}}: 
                <ul> 
                   
                    @foreach($row->packageTerms as $t)
                    <li style='display:inline-block; padding:0 20px 10px 20px;'>{{($t->term->name ) }}</li>
                    @endforeach
                </ul>
            </div>
            
            
          
            <div class="control-action">
           
                {{-- <form action='{{route('hotel.vendor.room.bookRoomPackage')}}' method='post'>
                    @csrf
                    <input type='hidden' name='package_id' value={{$row->id}}>
                    <input type='date' name='from' placeholder="{{__('Check In')}}" required>
                    <input type='date' name='to' placeholder="{{__('Check Out')}}" required>
                   
                     <button  class="btn btn-success">{{__(" Book Package")}}</button>
                </form> --}}
                <a  href={{route('package.checkout',$row->id)}} class="btn btn-success">{{__(" Book Package")}}</a>

            </div>
            
            {{-- <div class="control-action">
                @if(Auth::user()->hasPermissionTo('hotel_update'))
                <a href="{{route('hotel.vendor.room.createPackage', $row->id)}}" class="btn btn-success">{{__("Create Package")}}</a>

                    <a href="{{route('hotel.vendor.room.edit',['hotel_id'=>$hotel->id,'id'=>$row->id])}}" class="btn btn-warning">{{__("Edit")}}</a>
                @endif
                @if(Auth::user()->hasPermissionTo('hotel_update'))
                    <a href="{{route('hotel.vendor.room.delete',['hotel_id'=>$hotel->id,'id'=>$row->id])}}" class="btn btn-danger" data-confirm="<?php echo e(__("Do you want to delete?")); ?>">{{__("Del")}}</a>
                @endif
                @if($row->status == 'publish')
                    <a href="{{route('hotel.vendor.room.bulk_edit',['hotel_id'=>$hotel->id,'id'=>$row->id,'action' => "make-hide"])}}" class="btn btn-secondary">{{__("Make hide")}}</a>
                @endif
                @if($row->status == 'draft')
                    <a href="{{route('hotel.vendor.room.bulk_edit',['hotel_id'=>$hotel->id,'id'=>$row->id,'action' => "make-publish"])}}" class="btn btn-success">{{__("Make publish")}}</a>
                @endif
            </div> --}}
        </div>
    </div>
</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bravo-pagination">
                {{-- <span class="count-string">{{ __("Showing :from - :to of :total Hotels",["from"=>$rows->firstItem(),"to"=>$rows->lastItem(),"total"=>$rows->total()]) }}</span> --}}
                {{-- {{$rows->appends(request()->query())->links()}} --}}
            </div>
        </div>
    @else
        {{__("No Packages")}}
    @endif
@endsection
@section('footer')

@endsection
