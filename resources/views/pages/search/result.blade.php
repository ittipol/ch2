@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <?php 
    echo Form::open(['id' => 'main_form','method' => 'get', 'enctype' => 'multipart/form-data']);
  ?>

  <div class="row">
    <div class="col-sm-12 space-top-50 search-box-panel">
      <input type="text" id="search_query_input" name="search_query" value="{{$q}}" placeholder="ค้นหา" autocomplete="off" class="search-box">
      <button class="button-search">
        <img src="/images/icons/search-black.png">
      </button>
    </div>
  </div>

  <?php
    echo Form::close();
  ?>

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
                  <div class="price">{{$data['_price']}}</div>
                  @endif
                </div>

              </div>

            </div>

          </div>

          @endforeach

        </div>

        @include('components.pagination') 

      @else

      <div class="shop-notice text-center space-top-20">
        <img class="space-bottom-20" src="/images/common/not-found.png">
        <div>
          <h3>ขออภัย ไม่พบสิ่งที่คุณกำลังค้นหา</h3>
          <p>โปรดลองค้นหาอีกครั้งด้วยคำค้นหาที่แตกต่างจากคำค้นหานี้</p>
        </div>
      </div>

      @endif

    </div>

  </div>

</div>

@stop