@extends('layouts.blackbox.main')
@section('content')

@include('pages.product.layouts.fixed_top_nav')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">
          โปรโมชั่นการขาย
        </div>
      </div>
    </div>
  </div>

</div>

<div class="container">
  
  <div class="tile-nav-group space-top-bottom-20 clearfix">

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{$productDiscountAdd}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{$productDiscountAdd}}">
          <h4 class="tile-nav-title">เพิ่มโปรโมชั่นลดราคาสินค้า</h4>
        </a>
      </div>
    </div>

  </div>

  @if(!empty($salePromotions))

    <div class="space-top-bottom-20">
      <h4>ลำดับการทำงานของโปรโมชั่น</h4>
      <div class="line"></div>
    </div>
    
    @foreach($salePromotions as $salePromotion)

      @if($salePromotion['active'])
        <div class="list-box active">
          <h4>โปรโมชั่นนี้กำลังถูกใช้งาน</h4>
          <div class="text-center space-bottom-20">
            <img class="primary-image" src="/images/common/tick.png">
            <h4>ราคาสินค้าปกติ: {{$originalPrice}}</h4>
            <h4>ราคาสินค้าหลังลดราคา: {{$salePromotion['data']['_reduced_price']}}</h4>
          </div>

          <h5>ประเภทโปรโมชั่น: {{$salePromotion['data']['salePromotionType']}}</h5>
          <h5>ระยะเวลา: {{$salePromotion['data']['_date_start']}} - {{$salePromotion['data']['_date_end']}}</h5>

          <div class="additional-option">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$salePromotion['editUrl']}}">แก้ไข</a>
              <a href="{{$salePromotion['deleteUrl']}}" data-modal="1" data-modal-title="ต้องการลบใช่หรือไม่">ลบ</a>
            </div>
          </div>

        </div>
      @else
        <div class="list-box">
          <h4>โปรโมชั่นจะทำงานในอีก {{$salePromotion['remainingDays']}}</h4>
          <div class="text-center space-top-bottom-30">
            <h4>ราคาสินค้าปกติ: {{$originalPrice}}</h4>
            <h4>ราคาสินค้าหลังลดราคา: {{$salePromotion['data']['_reduced_price']}}</h4>
          </div>

          <h5>ประเภทโปรโมชั่น: {{$salePromotion['data']['salePromotionType']}}</h5>
          <h5>ระยะเวลา: {{$salePromotion['data']['_date_start']}} - {{$salePromotion['data']['_date_end']}}</h5>

          <div class="additional-option">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$salePromotion['editUrl']}}">แก้ไข</a>
              <a href="{{$salePromotion['deleteUrl']}}" data-modal="1" data-modal-title="ต้องการลบใช่หรือไม่">ลบ</a>
            </div>
          </div>

        </div>
      @endif
        
    @endforeach

  @else

    <div class="list-empty-message text-center space-top-20">
      <img src="/images/common/not-found.png">
      <div>
        <h3>โปรโมชั่น</h3>
        <p>สินค้านี้ยังไม่มีโปรโมชั่น</p>
        <a href="{{$productDiscountAdd}}" class="button">เพิ่มโปรโมชั่นลดราคาสินค้า</a>
      </div>
    </div>

  @endif

</div>

@stop