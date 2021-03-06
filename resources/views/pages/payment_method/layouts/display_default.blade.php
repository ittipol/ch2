<div class="row">

  @foreach($paymentMethod['data'] as $data)

  <div class="col-md-12 col-xs-6">
    
    <div class="payment-method-item space-bottom-20">
      <div class="row">
        <div class="col-md-8 col-xs-12">
          <h5 class="color-blue-2"><img src="/images/icons/bell-header.png">{{$data['name']}}</h5>
        </div>
        @if(!empty($data['description']))
        <div class="col-md-4 col-xs-12">
          <a data-right-side-panel="1" data-right-side-panel-target="#payment_method_{{$data['id']}}" class="button wide-button text-center">แสดงเพิ่มเติม</a>
        </div>
        @endif
      </div>
    </div>

    @include('pages.payment_method.layouts.more_detail_panel')

    </div>

  @endforeach

</div>