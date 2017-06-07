@foreach($paymentMethod['data'] as $data)
  
  <div class="payment-method-item space-bottom-20">

    <div class="row">
      <div class="col-md-8 col-xs-6">
        <div>
          <strong class="color-blue-2">หมายเลขสำหรับการโอน</strong>
          <div>{{$data['promptpay_transfer_number_type']}}: {{$data['promptpay_transfer_number']}}</div>
        </div>
      </div>
      @if(!empty($data['description']))
      <div class="col-md-2 col-xs-6">
        <a data-right-side-panel="1" data-right-side-panel-target="#payment_method_{{$data['id']}}" class="button">แสดงเพิ่มเติม</a>
      </div>
      @endif
    </div>

  </div>

  @include('pages.payment_method.layouts.more_detail_panel')

@endforeach