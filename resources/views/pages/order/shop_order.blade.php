@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage/product" class="btn btn-secondary">กลับไปยังหน้าหลักจัดการสินค้า</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage">ไปยังหน้าจัดการหลัก</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="container space-top-30 list">

  <h3>รายการสั่งซื้อสินค้า</h3>
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

    <div class="list-h">
    @foreach($_pagination['data'] as $data)
      <div class="list-h-item clearfix">

        <div class="list-image pull-left">
          <a href="{{$data['detailUrl']}}">
            <img src="/images/icons/bag-white.png">
          </a>
        </div>

        <div class="col-md-11 col-xs-8">

          <div class="row">

            <div class="col-md-4 col-xs-12 list-content">
              <a href="{{$data['detailUrl']}}">
                <h4 class="primary-info">เลขที่การสั่งซื้อ: {{$data['invoice_number']}}</h4>
              </a>
              <div class="secondary-info">สั่งซื้อเมื่อ: {{$data['orderedDate']}}</div>
            </div>

            <div class="col-md-4 col-xs-12 list-content">
              <h4 class="primary-info">สถานะการสั่งซื้อ</h4>
              <div class="secondary-info">{{$data['OrderStatusName']}}</div>
            </div>

            <div class="col-md-4 col-xs-12 list-content">
              <h4 class="primary-info">ยอดสุทธิ</h4>
              <div class="secondary-info">{{$data['_total']}}</div>
            </div>

          </div>

        </div>
        
        <div class="additional-option">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="additional-option-content">
            <a href="{{$data['detailUrl']}}">แสดงรายละเอียด</a>
            <a href="">ลบ</a>
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
      <h3>ยังไม่มีรายการสั่งซื้อสินค้าจากลูกค้า</h3>
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