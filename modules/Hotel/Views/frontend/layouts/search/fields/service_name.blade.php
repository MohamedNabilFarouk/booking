<div class="form-group">
    <i class="field-icon fa icofont-search"></i>
    <div class="form-content">
        <label>{{ $field['title'] }}</label>
        <div class="input-search">
            <input id="service_name" type="text" name="service_name" class="form-control" placeholder="{{__("Search for...")}}" value="{{ request()->input("service_name") }}">

            <ul id="myULHotels">

            </ul>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('#myULHotels').hide();
        $('#service_name').on("keyup",function(){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            var service_name = this.value;
            if(service_name.length != 0){
                var formData = {
                    title: service_name
                };

                $.ajax({
                    type:'GET',
                    url:'api/hotel/inputSearch',
                    data: formData,
                    dataType: 'json',
                    success:function(data) {
                        $('#myULHotels').html("");
                        for(var i = 0; i <= data.hotels.length; i++){
                            if(data.hotels[i] && data.hotels[i].title){
                                $('#myULHotels').append( "<li data-title='"+data.hotels[i].title+"'>" + data.hotels[i].title  + "</li>" );
                            }
                        }
                        $('#myULHotels').show();
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }else{
                $('#myULHotels').hide();
            }

        });


    });
    $(document).on('click','#myULHotels li',function(e) {
        //handler code here
        $('#service_name').val($(this).data('title'));
        $('#myULHotels').hide();
    });
</script>
<style>
    #myULHotels{
        transition: all 0.3s;
        position: absolute;
        background: #fff;
        padding: 0;
        top: 100%;
        margin-top: 15px;
        left: auto;
        border-radius: 0 0 5px 5px;
        border: solid 1px #dee2e6;
        z-index: 20;
        max-height: 300px;
        min-width: 201px;
        overflow-y: auto;
        right: -15px;
    }
    #myULHotels li {
        padding: 10px 25px;
        cursor: pointer;
    }
    #myULHotels li:hover{
        background: #f5f4f7
    }
</style>
