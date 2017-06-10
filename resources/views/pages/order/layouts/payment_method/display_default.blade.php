<div class="row">

  @foreach($paymentMethod['data'] as $data)

  <div class="col-md-12 col-xs-6">
    
    <div class="payment-method-item">
      <div class="row">
        <div class="col-md-8 col-xs-12">
          <h5 class="color-blue-2"><img src="/images/icons/bell-header.png">{{$data['name']}}</h5>
        </div>
        <div class="col-md-4 col-xs-12">
          <div class="card no-border">
            <div class="button-group">

              <a href="{{$data['informUrl']}}">
                <div class="button wide-button">แจ้งการชำระเงินด้วยวีธีการนี้</div>
              </a>

              @if(!empty($data['description']))
              <div class="additional-option">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="additional-option-content">
                  <a data-right-side-panel="1" data-right-side-panel-target="#payment_method_{{$data['id']}}">แสดงเพิ่มเติม</a>
                </div>
              </div>
              @endif
            
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('pages.order.layouts.payment_method.more_detail_panel')

  </div>

  @endforeach

</div>