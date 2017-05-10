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

          @foreach($results as $result)

          <div class="col-xs-12 list">

            <div class="list-content-wrapper clearfix">

              <div class="flag data-form-flag">{{$result['isDataTitle']}}</div>

              <div class="list-content image-tile">
                <a href="{{$result['detailUrl']}}">
                  <div class="primary-image" style="background-image:url({{$result['image']}});"></div>
                </a>
              </div>

              <div class="list-content info">
                <a href="{{$result['detailUrl']}}">
                  <div class="title">{{$result['title']}}</div>
                </a>

                @if(!empty($result['flags']))
                  @foreach($result['flags'] as $flag)
                    @if(!empty($flag))
                      <div class="flag">{{$flag}}</div>
                    @endif
                  @endforeach
                @endif

                @if(!empty($result['description']))
                  <div class="description">{{$result['description']}}</div>
                @endif

                @if(!empty($result['data']))

                  @foreach($result['data'] as $type => $data)

                    <?php
                      if(empty($data['value'])) continue;
                    ?>

                    <div class="result-data-content">

                    @if(!empty($data['title']))
                      <div class="data-title">{{$data['title']}}</div>
                    @endif

                    <?php
                      switch ($type) {
                        case 'productPrice':
                    ?>
                          @if(!empty($data['value']['promotion']))
                            <span class="price">{{$data['value']['promotion']['_reduced_price']}}</span>
                            <span class="price-discount-tag">{{$data['value']['promotion']['percentDiscount']}}</span>
                            <h5 class="origin-price">{{$data['value']['price']}}</h5>
                          @elseif(!empty($data['value']['price']))
                            <span class="price">{{$data['value']['price']}}</span>
                          @endif
                    <?php
                          break;
                    
                        case 'price':
                    ?>
                          <span class="price">{{$data['value']}}</span>
                    <?php
                          break;

                        case 'address':
                    ?>
                          <img class="data-icon" src="/images/common/location-pin.png">
                          <span class="data-value">{{$data['value']}}</span>
                    <?php
                          break;

                        case 'openHours':
                    ?>
                          <div class="additional-option after-text-icon black-color shop-open-sign {{$data['value']['status']}} space-top-10">
                            {{$data['value']['text']}}
                            <div class="additional-option-content">
                              <div class="shop-time-table-wrapper">
                              @foreach($data['value']['timeTable'] as $time)
                                <div class="shop-time-table clearfix">
                                  <div class="shop-time-table-day pull-left">{{$time['day']}}</div>
                                  <div class="shop-time-table-time pull-left">{{$time['openHour']}}</div>
                                </div>
                              @endforeach
                              </div>
                            </div>
                          </div>
                    <?php
                          break;
                        
                        default:
                      ?>
                          <span class="data-value">{{$data['value']}}</span>
                      <?php  
                          break;
                      }

                    ?>

                    </div>

                  @endforeach

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
          <h3>ขออภัย ไม่พบผลลัพธ์ที่ตรงกับคำค้นหา</h3>
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