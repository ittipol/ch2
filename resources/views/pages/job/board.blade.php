@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">งานจากบริษัทและร้านค้า</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="search sm">

    <?php 
      echo Form::open(['url' => 'search', 'id' => 'search_form','method' => 'get', 'enctype' => 'multipart/form-data']);
    ?>

    <div class="search-box-panel">
      <input type="text" name="search_query" placeholder="ค้นหางาน..." autocomplete="off" class="search-box">
      <button class="button-search">
        <i class="fa fa-search"></i>
      </button>
    </div>

    <input type="hidden" name="fq" value="model:Job">
    <input type="hidden" name="sort" value="name:asc">

    <?php
      echo Form::close();
    ?>

  </div>

  @foreach($boards as $board)

    <div class="shelf">

      <h3>{{$board['typeName']}}</h3>

      <h2>{{$board['total']}}</h2>
      <h5>รายการประกาศงาน{{$board['typeName']}}</h5>

      <div class="row">

        <div class="col-xs-12">

          @if(!empty($board['data']))

          <div class="row">

            @foreach($board['data']['items'] as $item)

              <div class="col-md-3 col-xs-6">

                <div class="card sm">

                  <div class="image-tile">
                    <a href="{{$item['detailUrl']}}">
                      <div class="card-image" style="background-image:url({{$item['_imageUrl']}});"></div>
                    </a>
                  </div>

                  <div class="card-info">

                    <a href="{{$item['detailUrl']}}">
                      <div class="card-title">{{$item['_short_name']}}</div>
                    </a>

                    <div class="card-sub-info">

                      <div class="card-sub-info">
                        <h5>อัตราค่าจ้าง</h5>
                        <div class="text-emphasize">{{$item['_wage']}}</div>
                      </div>

                    </div>

                  </div>

                </div>

              </div>

            @endforeach

            @if(!empty($board['data']['all']))
              <div class="col-xs-12">
                <a href="{{$board['itemBoardUrl']}}" class="product-all-tile">
                  <span>
                    แสดงตำแหน่งงานทั้งหมด<br>
                    {{$board['data']['all']['title']}}
                  </span>
                </a>
              </div>
            @endif

          </div>

          @else

            <div class="list-empty-message text-center space-top-20">
              <div>
                <h4>ยังไม่มีตำแหน่งงานประเภท{{$board['typeName']}}</h4>
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