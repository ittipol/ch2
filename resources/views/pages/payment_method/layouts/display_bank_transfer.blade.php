@foreach($paymentMethod['data'] as $data)
  
  <div class="payment-method-item space-bottom-20">

    <h5><img src="{{$data['providerLogo']}}" class="provider-logo">{{$data['providerName']}}</h5>

    <div class="row">
      <div class="col-md-2 col-xs-6">
        <div>
          <strong class="color-blue-2">ชื่อบัญชี</strong>
          <div>{{$data['account_name']}}</div>
        </div>
      </div>
      <div class="col-md-2 col-xs-6">
        <div>
          <strong class="color-blue-2">ประเภทบัญชี</strong>
          <div>{{$data['account_type']}}</div>
        </div>
      </div>
      <div class="col-md-2 col-xs-6">
        <div>
          <strong class="color-blue-2">สาขา</strong>
          <div>{{$data['branch_name']}}</div>
        </div>
      </div>
      <div class="col-md-2 col-xs-6">
        <div>
          <strong class="color-blue-2">เลขที่บัญชี</strong>
          <div>{{$data['account_number']}}</div>
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