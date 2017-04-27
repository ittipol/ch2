@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('item/post')}}" class="btn btn-secondary">เพิ่มประกาศ</a>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <h2>ประกาศซื้อ-เช่า-ขายสินค้า</h2>
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

          @if(!empty($board['data']))

          <div class="row">

            @foreach($board['data']['items'] as $item)

              <div class="col-lg-3 col-sm-4 col-xs-12">

                <div class="card">

                  <div class="flag-wrapper">
                    <div class="flag">{{$item['_announcementTypeName']}}</div>
                  </div>
                  
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
                      <h5>ราคา{{$item['_announcementTypeName']}}</h5>
                      <div class="text-emphasize">{{$item['_price']}}</div>
                    </div>

                  </div>

                </div>

              </div>

            @endforeach

            @if(!empty($board['data']['all']))
              <div class="col-lg-3 col-xs-12">
                <a href="{{$board['itemBoardUrl']}}" class="product-all-tile">
                  <span>
                    แสดงประกาศทั้งหมด<br>
                    {{$board['data']['all']['title']}}
                  </span>
                </a>
              </div>
            @endif

          </div>

          @else

            <div class="list-empty-message text-center space-top-20">
              <div>
                <h4>ยังไม่มีประกาศสินค้าหมวด{{$board['categoryName']}}</h4>
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