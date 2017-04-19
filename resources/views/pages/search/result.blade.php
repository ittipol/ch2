@extends('layouts.blackbox.main')
@section('content')

<div class="container search">

  <?php 
    echo Form::open(['id' => 'search_form','method' => 'get', 'enctype' => 'multipart/form-data']);
  ?>

  <div class="row">
    <div class="col-sm-12 space-top-50 search-box-panel">
      <input type="text" name="search_query" value="{{$q}}" placeholder="ค้นหา" autocomplete="off" class="search-box">
      <button class="button-search">
        <img src="/images/icons/search.png">
      </button>
    </div>
  </div>

  <?php
    echo Form::close();
  ?>

  <div id="filter_expand_panel" class="right-size-panel filter">
    <div class="right-size-panel-inner">
      @include('components.filter_expand_panel')
      <div class="right-size-panel-close-button"></div>
    </div>
  </div>

  <div class="displaying-filters">

    @foreach($displayingFilters['filters'] as $filters)

      @if(!empty($filters['display']))

        <h5>{{$filters['title']}}</h5>

        @foreach($filters['display'] as $display)
          <div class="filter-tag">{{$display}}</div>
        @endforeach

      @endif

    @endforeach

    @if(!empty($displayingFilters['sort']['display']))

      <h5>{{$displayingFilters['sort']['title']}}</h5>

      @foreach($displayingFilters['sort']['display'] as $display)
        <div class="filter-tag">{{$display}}</div>
      @endforeach

    @endif

  </div>

  <div class="text-right space-top-20">
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#filter_expand_panel">
      ตัวกรอง
    </a>
  </div>

  <div class="result-container space-top-50">

    <h3 class="result-count space-bottom-20">
      {{$count}} ผลลัพธ์ที่ตรงกับคำค้นหา
    </h3>

    <div class="line"></div>

    <div class="lists search-results">

      @if(!empty($count) && !empty($results))

        <div class="row">

          @foreach($results as $data)

          <div class="col-xs-12 list">

            <div class="list-content-wrapper clearfix">

              <div class="flag data-form-flag">{{$data['dataFromFlag']}}</div>

              <div class="list-content image">
                <a href="{{$data['_detailUrl']}}">
                  <div class="primary-image" style="background-image:url({{$data['_imageUrl']}});"></div>
                </a>
              </div>

              <div class="list-content info">
                <a href="{{$data['_detailUrl']}}">
                  <div class="title">{{$data['_short_name']}}</div>
                </a>

                @if(!empty($data['flag']))
                  <div class="flag">{{$data['flag']}}</div>
                @endif

                @if(!empty($data['need_broker']))
                  <div class="flag">ต้องการตัวแทนขาย</div>
                @endif

                @if(!empty($data['_short_description']) && ($data['_short_description'] != '-'))
                  <div class="description">{{$data['_short_description']}}</div>
                @endif

                @if(!empty($data['promotion']))
                  <span class="price">{{$data['promotion']['_reduced_price']}}</span>
                  <span class="price-discount-tag">{{$data['promotion']['percentDiscount']}}</span>
                  <h5 class="origin-price">{{$data['_price']}}</h5>
                @elseif(!empty($data['_price']))
                  <div class="price">{{$data['_price']}}</div>
                @endif

              </div>

            </div>

          </div>

          @endforeach

        </div>

        @include('components.pagination') 

      @else

      <div class="list-empty-message text-center space-top-20">
        <img class="space-bottom-20 not-found-image" src="/images/common/not-found.png">
        <div>
          <h3>ขออภัย ไม่พบสิ่งที่คุณกำลังค้นหา</h3>
          <p>โปรดลองค้นหาอีกครั้งด้วยคำค้นหาที่แตกต่างจากคำค้นหานี้</p>
        </div>
      </div>

      @endif

    </div>

  </div>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const filter = new Filter();
    filter.load();

  });

</script>

@stop