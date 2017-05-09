@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUdwm6VWiEE-1ZrVgY3bh1sNX21_deHZw&libraries=places" type="text/javascript"></script>

@include('pages.shop.layouts.header')

<div class="shop-about-wrapper">

  <div class="container">
    <h3>เกี่ยวกับ</h3>
  </div>

  @if(!empty($_modelData['Address']['_geographic']))
    <div id="map" class="map-md"></div>
  @endif

  <div class="container space-top-50">

    <div class="row">

      <div class="col-md-6 col-xs-12">

        @if(!empty($openHours))
        <div class="shop-about-content">
          <h4>เวลาทำการ</h4>
          <div class="line"></div>
          
          <div class="shop-time-table-wrapper">
          @foreach($openHours['timeTable'] as $time)
            <div class="shop-time-table clearfix">
              <div class="shop-time-table-day pull-left">{{$time['day']}}</div>
              <div class="shop-time-table-time pull-left">{{$time['openHour']}}</div>
            </div>
          @endforeach
          </div>
          
        </div>
        @endif

        <div class="shop-about-content">
          <h4>ที่อยู่</h4>
          <div class="line"></div>

          <div class="shop-about-content-inner">
            @if(!empty($_modelData['Address']['_long_address']))
              {{$_modelData['Address']['_long_address']}}
            @else
              -
            @endif
          </div>

        </div>

        <div class="shop-about-content">
          <h4>การติดต่อ</h4>
          <div class="line"></div>

          <div class="shop-about-content-inner">
            <div>
              <h5><strong>หมายเลขโทรศัพท์</strong></h5>
              @if(!empty($_modelData['Contact']['phone_number']))
                {{$_modelData['Contact']['phone_number']}}
              @else
                -
              @endif
            </div>

            <div>
              <h5><strong>อีเมล</strong></h5>
              @if(!empty($_modelData['Contact']['email']))
                {{$_modelData['Contact']['email']}}
              @else
                -
              @endif
            </div>

            <div>
              <h5><strong>เว็บไซต์</strong></h5>
              @if(!empty($_modelData['Contact']['website']))
                {{$_modelData['Contact']['website']}}
              @else
                -
              @endif
            </div>
          </div>

        </div>

      </div>

      <div class="col-md-6 col-xs-12">

        <div class="shop-about-content">
          <h4>คำอธิบายเกี่ยวกับ</h4>
          <div class="line"></div>

          <div class="shop-about-content-inner">
            @if(!empty($about['description']))
              {!!$about['description']!!}
            @else
              -
            @endif
          </div>

        </div>

        @if(!empty($about['brand_story']))
        <div class="shop-about-content">
          <h4>เรื่องราว (Brand Story)</h4>
          <div class="line"></div>

          <div class="shop-about-content-inner">
            {!!$about['brand_story']!!}
          </div>

        </div>
        @endif

        @if(!empty($about['vision']))
        <div class="shop-about-content">
          <h4>วิสัยทัศน์</h4>
          <div class="line"></div>

          <div class="shop-about-content-inner">
            {!!$about['vision']!!}
          </div>

        </div>
        @endif

        @if(!empty($about['mission']))
        <div class="shop-about-content">
          <h4>พันธกิจ</h4>
          <div class="line"></div>

          <div class="shop-about-content-inner">
            {!!$about['mission']!!}
          </div>

        </div>
        @endif

      </div>

    </div>

  </div>

</div>

<script type="text/javascript">
  $(document).ready(function(){
    @if(!empty($_modelData['Address']['_geographic']))
    const map = new Map(false,false,false);
    map.initialize();
    map.setLocation({!!$_modelData['Address']['_geographic']!!});
    @endif
  });
</script>

@stop