@foreach($paymentMethod['data'] as $data)
  
  <div class="payment-method-item space-bottom-20">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <h5 class="color-blue-2"><img src="/images/icons/bell-header.png">{{$data['name']}}</h5>
      </div>
      @if(!empty($data['description']))
      <div class="col-md-2 col-xs-12">
        <a data-right-side-panel="1" data-right-side-panel-target="#payment_method_{{$data['id']}}" class="button">แสดงเพิ่มเติม</a>
      </div>
      @endif
    </div>
  </div>

  @include('pages.payment_method.layouts.more_detail_panel')

@endforeach