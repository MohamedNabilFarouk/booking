@extends ('admin.layouts.app')

@section ('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__('Orders')}}</h1>
        </div>
        @include('admin.message')
        <div class="text-right">
            <?php
            $pag =  empty($_GET['type']) ? false : true
            ?>
            @if($pag == false)
                <p><i>{{__('Found :total items',['total'=>$rows->total()])}}</i></p>
            @else
                <p><i>{{__('Found :total items',['total'=>count($rows)])}}</i></p>
            @endif
        </div>
        <div class="panel booking-history-manager">
            <div class="panel-title">{{__('Orders')}}</div>
            <div class="panel-body">
                <form action="" class="bravo-form-item">
                    <table class="table table-hover bravo-list-item">
                        <thead>
                        <tr>
                            <th width="30px">#</th>
                            <th>{{__('Service')}}</th>
                            <th>{{__('Customer')}}</th>

                            <th>{{__('Payment Information')}}</th>
                            <th width="120px">{{__('Created At')}}</th>
                            <th width="120px">{{__('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rows as $index=>$row )

                            @php
                                $booking = $row;
                            @endphp

                            <tr @if($index == 0) class="d-none" @endif>
                                <td>{{$index}}</td>
                                <td>
                                    @if($service = $row->service)
                                        <a href="{{$service->getDetailUrl()}}" target="_blank">{{$service->title ?? ''}}</a>
                                        @if($row->vendor)
                                            <br>
                                            <span>{{__('by')}}</span>
                                            <a href="{{url('admin/module/user/edit/'.$row->vendor_id)}}"
                                               target="_blank">{{$row->vendor->name_or_email.' (#'.$row->vendor_id.')' }}</a>
                                        @endif
                                    @else
                                        {{__("[Deleted]")}}
                                    @endif
                                </td>
                                <td>
                                    @if($row->customer)
                                    <ul>
                                        <li>{{__("Name:")}} {{$row->customer->name}} </li>
                                        <li>{{__("Email:")}} {{$row->customer->email}}</li>
                                        <li>{{__("Phone:")}} {{$row->customer->phone}}</li>
                                        <li>{{__("Address:")}} {{$row->customer->address}}</li>
                                    </ul>
                                    @endif
                                </td>
                                <td>{{__("Total")}} : {{format_money_main($row->total)}}<br/>
                                <!-- {{__("Paid")}} : {{format_money_main($row->paid)}}<br/> -->
                                <!-- {{__("Remain")}} : {{format_money_main($booking->total - $booking->paid)}}<br/> -->
                                </td>
                                <td>{{display_datetime($row->created_at)}}</td>
                                <td>
                                    <a href="{{route('report.admin.orderConfirm',[$row->id])}}" class="btn btn-block btn-info"><i class="fa fa-connectdevelop"></i> {{__('Confirm')}}</a>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-block btn-danger" data-toggle="modal" data-target="#orderCancelConfirm{{$row->id}}">
                                        <i class="fa fa-trash"></i> {{__('Cancel')}}
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="orderCancelConfirm{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                            <form action="{{route('report.admin.orderCancelConfirm',[$row->id])}}" method="GET">
                                                <div class="modal-header">
                                                        <h5 class="modal-title" >{{__("order cancel confirm")}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if($row->serviceLocations())
                                                            @foreach($row->serviceLocations() as $hotel)
                                                                <div class="form-group d-flex align-items-center mb-3">
                                                                    <input type="checkbox" class="form-check-input" name="hotels[]" value="{{$hotel->id}}" id="{{$row->id}}hotel{{$hotel->id}}">
                                                                    <label class="col-form-label ml-4" for="{{$row->id}}hotel{{$hotel->id}}">{{$hotel->title}}</label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">{{__("confirm cancel")}}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{--                                    <a href="{{route('report.admin.orderCancelConfirm',[$row->id])}}" class="btn btn-block btn-danger"><i class="fa fa-trash"></i> {{__('Cancel')}}</a>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            @if($pag == false)
                {{$rows->links()}}
            @endif
        </div>
    </div>
@endsection
@section('script.body')
    <script>
        $(document).on('click', '#set_paid_btn', function (e) {
            var id = $(this).data('id');
            $.ajax({
                url:bookingCore.url+'/booking/setPaidAmount',
                data:{
                    id: id,
                    remain: $('#modal-paid-'+id+' #set_paid_input').val(),
                },
                dataType:'json',
                type:'post',
                success:function(res){
                    alert(res.message);
                    window.location.reload();
                }
            });
        });
    </script>
@endsection
