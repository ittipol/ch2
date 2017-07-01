@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('shop/create')}}" class="btn btn-secondary">สร้างบริษัทหรือร้านค้า</a>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="container list space-top-30">

  <h3>บริษัทและร้านค้า</h3>
  <div class="line"></div>
  <div class="text-right space-top-bottom-20">
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#filter_expand_panel">ตัวกรอง</a>
  </div>

  <div id="filter_expand_panel" class="right-size-panel filter">
    <div class="right-size-panel-inner">
      @include('components.filter_expand_panel')
      <div class="right-size-panel-close-button"></div>
    </div>
  </div>

  @if(!empty($_pagination['data']))

    <div class="row">

      @foreach($_pagination['data'] as $data)

      <div class="col-lg-3 col-sm-4 col-xs-6">
        <div class="card sm">

          @if(!empty($data['openHours']))
          <div class="floating-data shop-open-hours">
            <div class="additional-option shop-open-sign  after-text-icon {{$data['openHours']['status']}}">
              {{$data['openHours']['text']}}
              <div class="additional-option-content">
                <div class="shop-time-table-wrapper">
                @foreach($data['openHours']['timeTable'] as $time)
                  <div class="shop-time-table clearfix">
                    <div class="shop-time-table-day pull-left">{{$time['day']}}</div>
                    <div class="shop-time-table-time pull-left">{{$time['openHour']}}</div>
                  </div>
                @endforeach
                </div>
              </div>
            </div>
          </div>
          @endif

          <div class="image-tile">
            <a href="{{$data['shopUrl']}}">
              <div class="card-image cover" style="background-image:url({{$data['profileImage']}});"></div>
            </a>
          </div>
          <div class="card-info">
            <a href="{{$data['shopUrl']}}">
              <div class="card-title">{{$data['name']}}</div>
            </a>
            <div class="card-desciption">
              {!!$data['description']!!}
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
      <h3>ไม่พบบริษัทและร้านค้า</h3>
    </div>
  </div>

  @endif

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const filter = new Filter(true);
    filter.load();

  });

</script>

@stop