@extends('layouts.blackbox.main')
@section('content')

<div class="container">

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

  <div id="filters" class="right-size-panel filter">
    <div class="right-size-panel-inner">
      @include('components.search_filter')
      <div class="right-size-panel-close-icon"></div>
    </div>
  </div>

  <?php
    echo Form::close();
  ?>

  <div class="displaying-filters">

    @foreach($displayingFilters['filters'] as $filters)

      <h4>{{$filters['title']}}</h4>
      <!-- <div class="line"></div> -->

      @foreach($filters['display'] as $display)
        <div class="filter-tag">{{$display}}</div>
      @endforeach

    @endforeach

    <h4>จัดเรียงตาม</h4>
    <!-- <div class="line"></div> -->
    <div class="filter-tag">{{$displayingFilters['sort']}}</div>

  </div>

  <div class="text-right space-top-20">
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#filters">
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

          <div class="col-xs-12">

            <div class="list">

              <div class="list-content-wrapper clearfix">

                <div class="list-content image">
                  <a href="{{$data['_detailUrl']}}">
                    <div class="primary-image" style="background-image:url({{$data['_imageUrl']}});"></div>
                  </a>
                </div>

                <div class="list-content info">
                  <a href="{{$data['_detailUrl']}}">
                    <div class="title">{{$data['_short_name']}}</div>
                  </a>

                  @if(!empty($data['_short_description']) && ($data['_short_description'] != '-'))
                  <div class="description">{{$data['_short_description']}}</div>
                  @endif

                  @if(!empty($data['_price']))
                  <div class="card-sub-info">{{$data['_price']}}</div>
                  @endif
                </div>

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

  class Filter {

    constructor() {}

    load() {
      this.bind();
    }

    bind() {

      // http://lv5.local/list?filter=type:value,type1:value1&q=company&sort=name:asc

      // ?fq=genre:action,genre:adventure,genre:puzzle&sort=dynamicPrice desc

      // ?search_query=Iphone 7 128GB Limited Edition&model=shop&model=product&model=job&model=advertising&model=item&model=real_estate&sort=name:asc

      // let filters = '';
      let filters = [];

      $('#search_form').on('submit',function(){

        $('.filter-model:checked').each(function(i, obj) {
            filters.push($(this).val());
        });

        var input = document.createElement('input');
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'fq');
        input.setAttribute('value', filters.join());

        this.appendChild(input);

      });

    }

    createData() {

    }

  }

  $(document).ready(function(){

    const filter = new Filter();
    filter.load();

  });

</script>

@stop