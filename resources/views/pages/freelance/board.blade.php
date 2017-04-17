@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <h2>ฟรีแลนซ์</h2>
    </div>
  </div>
</div>

<div class="container">

  @foreach($boards as $board)

    <div class="shelf">

      <h3>{{$board['typeName']}}</h3>

      <h2>{{$board['total']}}</h2>
      <h5>รายการประกาศ</h5>

      <div class="row">

        <div class="col-xs-12">

          @if(!empty($board['data']))

          <div class="row">

            @foreach($board['data']['items'] as $item)

              <div class="col-lg-3 col-sm-4 col-xs-12">

                <div class="card">

                  <div class="image-tile">
                    <a href="{{$item['detailUrl']}}">
                      <div class="card-image" style="background-image:url({{$item['_imageUrl']}});"></div>
                    </a>
                  </div>

                  <div class="card-info">

                    <a href="{{$item['detailUrl']}}">
                      <div class="card-title">{{$item['_short_name']}}</div>
                    </a>

                  </div>

                </div>

              </div>

            @endforeach

            @if(!empty($board['data']['all']))
              <div class="col-lg-3 col-xs-12">
                <a href="{{$board['itemBoardUrl']}}" class="product-all-tile">
                  <span>
                    แสดงประกาศงานทั้งหมด<br>
                    {{$board['data']['all']['title']}}
                    <img src="/images/common/tag.png">
                  </span>
                </a>
              </div>
            @endif

          </div>

          @else

            <div class="list-empty-message text-center space-top-20">
              <div>
                <h4>ยังไม่มีประกาศอสังหาริมทรัพย์ประเภท{{$board['typeName']}}</h4>
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