<div class="row">

  @foreach($paymentMethod['data'] as $data)

  <div class="col-md-12 col-xs-6">
    
    <div class="payment-method-item space-bottom-20">

      <div class="row">
        <div class="col-md-8 col-xs-6">
          <div>
            <strong class="color-blue-2">บัญชี PayPal</strong>
            <div>{{$data['paypal_account']}}</div>
          </div>
        </div>
        @if(!empty($data['description']))
        <div class="col-md-4 col-xs-6">
          <a data-right-side-panel="1" data-right-side-panel-target="#payment_method_{{$data['id']}}" class="button wide-button text-center">แสดงเพิ่มเติม</a>
        </div>
        @endif
      </div>

    </div>

    @include('pages.payment_method.layouts.more_detail_panel')

  </div>

  @endforeach

</div>