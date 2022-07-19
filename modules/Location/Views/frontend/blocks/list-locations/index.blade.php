@php   $slider_hodel = \Modules\Hotel\Models\Hotel::with('translations', 'location', 'hasWishList', 'termsByAttributeInListingPage')
        ->where('status', 'publish')->inRandomOrder()->take(5)->orderByDesc('id')->get();

        $slider_tours = \Modules\Tour\Models\Tour::with('translations', 'location', 'hasWishList')
        ->where('status', 'publish')->inRandomOrder()->orderByDesc('id')->take(5)->get();
@endphp

<div class="container">
    <div class="bravo-list-locations @if(!empty($layout)) {{ $layout }} @endif">
        <div class="title">
            {{$title}}
        </div>
        @if(!empty($desc))
            <div class="sub-title">
                {{$desc}}
            </div>
        @endif
        @if(!empty($rows))
            <div class="list-item" dir="ltr">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="slider-hotels mb-3">
                            <div id="tours-slider" class="carousel slide carousel-fade" data-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($slider_tours as $key=>$row)
                                        @php
                                            $translation = $row->translateOrOrigin(app()->getLocale());
                                        @endphp
                                        <div class="carousel-item  @if($key == 0) active @endif">
                                            <div class="destination-item ">
                                                <a href="{{$row->getDetailUrl()}}">
                                                    @if($row->discount_percent)
                                                        <div class="sale_info">{{$row->discount_percent}}</div>
                                                    @endif
                                                    <div class="image" style="background: url({{$row->image_url}})">
                                                        <div class="effect"></div>
                                                        <div class="content">
                                                            <h4 class="title">{!! clean($translation->title) !!}</h4>
                                                            <div class="desc">
                                                                @if($row->star_rate)
                                                                    <div class="star-rate">
                                                                        <div class="list-star">
                                                                            <ul class="booking-item-rating-stars">
                                                                                @for ($star = 1 ;$star <= $row->star_rate ; $star++)
                                                                                    <li><i class="fa fa-star"></i></li>
                                                                                @endfor
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <div class="location">
                                                                    @if(!empty($row->location->name))
                                                                        @php $location =  $row->location->translateOrOrigin(app()->getLocale()) @endphp
                                                                        {{$location->name ?? ''}}
                                                                        <i class="icofont-paper-plane"></i>
                                                                    @endif
                                                                </div>

                                                                <div class="info">
                                                                    <div class="g-price d-flex justify-content-center">
                                                                        <div class="price">
                                                                            <div class="onsale">{{ $row->display_sale_price }}</div>
                                                                            <div class="text-price">{{ $row->display_price }}</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#tours-slider" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#tours-slider" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="slider-hotels">
                            <div id="hotels-slider" class="carousel slide carousel-fade" data-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($slider_hodel as $key=>$row)
                                        @php
                                            $translation = $row->translateOrOrigin(app()->getLocale());
                                        @endphp
                                        <div class="carousel-item  @if($key == 0) active @endif">
                                            <div class="destination-item ">
                                                <a href="{{$row->getDetailUrl()}}">
                                                    <div class="image" style="background: url({{$row->image_url}})">
                                                        <div class="effect"></div>
                                                        <div class="content">
                                                            <h4 class="title">{!! clean($translation->title) !!}</h4>
                                                            <div class="desc">
                                                                @if($row->star_rate)
                                                                    <div class="star-rate">
                                                                        <div class="list-star">
                                                                            <ul class="booking-item-rating-stars">
                                                                                @for ($star = 1 ;$star <= $row->star_rate ; $star++)
                                                                                    <li><i class="fa fa-star"></i></li>
                                                                                @endfor
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <div class="location">
                                                                    @if(!empty($row->location->name))
                                                                        @php $location =  $row->location->translateOrOrigin(app()->getLocale()) @endphp
                                                                        {{$location->name ?? ''}}
                                                                        <i class="icofont-paper-plane"></i>
                                                                    @endif
                                                                </div>

                                                                <div class="info">
                                                                    <div class="g-price d-flex justify-content-center">
                                                                        <div class="prefix">
                                                                            <span class="fr_text">{{__("from")}}</span>
                                                                        </div>
                                                                        <div class="price">
                                                                            <span class="text-price">{{ $row->display_price }} <span class="unit">{{__("/night")}}</span></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#hotels-slider" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#hotels-slider" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 d-flex justify-space-between flex-column col-6">

                        <div class="slider-cities" style="margin-bottom: 20px;">
                            <div id="cities1" class="carousel slide carousel-fade" data-ride="carousel">
                              <div class="carousel-inner">
                                    @foreach($rows->slice(0, 3) as $key=>$row)

                                        <div class="carousel-item  @if($key == 0) active @endif">
                                            @include('Location::frontend.blocks.list-locations.loop')
                                        </div>

                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#cities1" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#cities1" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>

                        <div class="slider-cities">
                            <div id="cities2" class="carousel slide carousel-fade" data-ride="carousel">
                              <div class="carousel-inner">
                                    @foreach($rows->slice(3, 7) as $key=>$row)
                                        <div class="carousel-item  @if($key == 3) active @endif">
                                            @include('Location::frontend.blocks.list-locations.loop')
                                        </div>

                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#cities2" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#cities2" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{--  @foreach($rows as $key=>$row)
                          <?php
                        $size_col = 4;
                        if( !empty($layout) and (  $layout == "style_2" or $layout == "style_3" or $layout == "style_4" )){
                            $size_col = 4;
                        }else{
                            if($key == 0){
                                $size_col = 8;
                            }
                        }
                        ?>
                        <div class="col-lg-{{$size_col}} col-md-6">
                            @include('Location::frontend.blocks.list-locations.loop')
                        </div>
                    @endforeach  --}}
                </div>
            </div>
        @endif
    </div>
</div>

