
@extends('layouts.user')

@section('head')
{{-- data table --}}
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css'>

{{-- end datatable --}}

@endsection
@section('content')
    <h2 class="title-bar">
       {{__("Packages Booked Report")}}
       
    </h2>
    @include('admin.message')
    @if(count($package) > 0)
{{-- @dd($package) --}}
    {{-- @foreach($package as $p)
        @foreach($p->booking as $r)
                {{$r->id}}
        @endforeach
    @endforeach --}}



    <table class="table table-striped table-bordered table-sm" id="sort" cellspacing="0" width="100%">
        <thead>
          <tr>

            <th class="th-lg">{{__('Package')}}
      
            </th>
            <th class="th-sm">{{__('Name')}}
      
            </th>
            <th class="th-sm">{{__('Email')}}
      
            </th>
         
            <th class="th-sm">{{__('Phone')}}
      
            </th>
            <th class="th-sm">{{__('Check In')}}
      
            </th>

            <th class="th-sm">{{__('Check out')}}
      
            </th>
            <th class="th-sm">{{__('Price')}}
      
            </th>
            <th class="th-sm">{{__('Status')}}
            </th>
          </tr>
        </thead>
        <tbody>
           @foreach($package as $p)
        @foreach($p->booking as $r)
            <tr>
            <td>{{$p->name}} <br> {{$p->room->name}}</td>
            <td>{{$r->name}}</td>
            <td>{{$r->email}}</td>
            <td>{{$r->phone}}</td>
            <td>{{$r->from}}</td>
            <td>{{$r->to}}</td>
            <td>{{$r->total}}</td>
            <td>@if($r->is_paid == '1') Paid @else  Unpaid @endif</td>

          </tr>
          @endforeach
    @endforeach
        </tbody>
        <tfoot>
          <tr>
          <th class="th-lg">{{__('Package')}}
      
      </th>
      <th class="th-sm">{{__('Name')}}

      </th>
      <th class="th-sm">{{__('Email')}}

      </th>
   
      <th class="th-sm">{{__('Phone')}}

      </th>
      <th class="th-sm">{{__('Check In')}}

      </th>

      <th class="th-sm">{{__('Check out')}}

      </th>
      <th class="th-sm">{{__('Price')}}

      </th>
      <th class="th-sm">{{__('Status')}}
      </th>
          </tr>
        </tfoot>
      </table>
        
    @else
        {{__("No Packages")}}
    @endif

@endsection
@section('footer')
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
@endsection
