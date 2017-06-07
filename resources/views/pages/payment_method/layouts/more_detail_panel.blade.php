@if(!empty($data['description']))
<div id="payment_method_{{$data['id']}}" class="right-size-panel">
  <div class="right-size-panel-inner">
    <h4>{{$paymentMethod['name']}}</h4>
    <h4>{{$data['name']}}</h4>
    <div class="line space-bottom-10"></div>
    <h5>รายละเอียดวิธีการชำระเงิน</h5>
    {!!$data['description']!!}
  <div class="right-size-panel-close-button"></div>
  </div>
</div>
@endif