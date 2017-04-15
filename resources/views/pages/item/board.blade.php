@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <h2>ประกาศซื้อ-ขายสินค้า</h2>
    </div>
  </div>
</div>

<div class="container">

  @foreach($boards as $board)

    <div class="shelf">

      <h3>{{$board['categoryName']}}</h3>

      <h2>{{$board['total']}}</h2>
      <h5>รายการประกาศสินค้า</h5>

      <div class="row">

        <div class="col-xs-12">

          @if(!empty($board['items']))

          <div class="row">

            @foreach($board['items']['items'] as $item)

              <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">

                <div class="card">

                  <div class="image-tile">
                    <a href="{{$item['detailUrl']}}">
                      <div class="card-image" style="background-image:url({{$item['_imageUrl']}});"></div>
                    </a>
                  </div>

                  <div class="card-info">

                    <a href="{{$item['detailUrl']}}">
                      <div class="card-title">{{$item['name']}}</div>
                    </a>

                    <div class="card-sub-info">

                      <div class="card-sub-info-row product-price-section">
                        <span class="product-price">{{$item['_price']}}</span>
                      </div>

                    </div>

                  </div>

                </div>

              </div>

            @endforeach

            @if(!empty($board['items']['all']))
              <div class="col-lg-3 col-xs-12">
                <a href="{{$board['itemBoardUrl']}}" class="product-all-tile">
                  <span>
                    แสดงประกาศสินค้าทั้งหมด<br>
                    {{$board['items']['all']['title']}}
                    <img src="/images/common/tag.png">
                  </span>
                </a>
              </div>
            @endif

          </div>

          @else

            <div class="list-empty-message text-center space-top-20">
              <div>
                <h4>ยังไม่มีสินค้าหมวด{{$board['categoryName']}}</h4>
              </div>
            </div>

          @endif

        </div>

      </div>

    </div>

    <div class="line space-top-bottom-20"></div>
  @endforeach

</div>

@stop