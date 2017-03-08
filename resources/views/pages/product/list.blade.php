@extends('layouts.blackbox.main')
@section('content')

  <div class="container list">

    @if(!empty($_pagination['data']))

      <div class="row">

        @foreach($_pagination['data'] as $data)

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
          <div class="card">

            @if($data['hasPromotion'])
              <div class="message-tag sale-promotion">สินค้าโปรโมชั่น</div>
            @endif
            
            <div class="image-tile">
              <a href="{{$data['detailUrl']}}">
                <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            
            <div class="card-info">
              <a href="{{$data['detailUrl']}}">
                <div class="card-title">{{$data['name']}}</div>
              </a>
              <div class="card-sub-info">

                <div class="card-sub-info-row product-price-section">
                  @if($data['hasPromotion'])
                    <span class="product-price">{{$data['promotion']['_reduced_price']}}</span>
                    <span class="product-price-discount-tag">{{$data['promotion']['percentDiscount']}}</span>
                    <h5 class="origin-price">{{$data['_price']}}</h5>
                  @else
                    <span class="product-price">{{$data['_price']}}</span>
                  @endif
                </div>

              </div>
            </div>

          </div>
        </div>

        @endforeach

      </div>

      @include('components.pagination') 

    @else

    <div class="list-empty-message text-center space-top-20">
      <img class="space-bottom-20" src="/images/common/not-found.png">
      <div>
        <h3>ยังไม่มีข้อมูลซื้อ ขายสินค้า</h3>
        <p>ขออภัย ยังไม่มีข้อมูลซื้อ ขายสินค้า</p>
        <a href="{{URL::to('item/post')}}" class="button">เพิ่มข้อมูลซื้อ ขายสินค้า</a>
      </div>
    </div>

    @endif

  </div>

@stop