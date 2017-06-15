<div class="row">

  @foreach($paymentMethod['data'] as $data)

  <div class="col-md-12 col-xs-12">
    
    <div class="payment-method-item">

      <h5>
        <img src="{{$data['providerLogo']}}" class="provider-logo">
        {{$data['providerName']}}
      </h5>

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
        <div class="col-md-4 col-xs-12">

          <div class="card no-border">
            <div class="button-group">

              <a href="{{$data['informUrl']}}">
                <div class="button wide-button">แจ้งชำระเงินด้วยวีธีนี้</div>
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