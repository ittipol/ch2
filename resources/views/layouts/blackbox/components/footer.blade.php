<footer>

  <div class="container-fuild">

    <div class="footer-wrapper">

      <div class="row">

        <div class="footer col-xs-12">

          <div class="row">

            <div class="col-md-2 col-xs-12">
              <h4 class="footer-title">บริษัทและร้านค้า</h4>
              <div class="footer-content">
                <div class="footer-link">
                  <a href="{{URL::to('shop/create')}}">สร้างบริษัทหรือร้านค้า</a>
                </div>
                <div class="footer-link">
                  <a href="{{URL::to('product')}}">สินค้า</a>
                </div>
                <div class="footer-link">
                  <a href="{{URL::to('job')}}">งาน</a>
                </div>
              </div>
            </div>

            <div class="col-md-2 col-xs-12">
              <h4 class="footer-title">การช่วยเหลือ</h4>
              <div class="footer-content">
                <div class="footer-link">
                  <a href="{{URL::to('manual')}}">วิธีการใช้งาน</a>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>

      <div class="line white space-top-bottom-30"></div>

      <div class="row">
        <div class="col-xs-6">
          <div class="footer-content">
            <img src="/images/footer-logo-no-margin.png">
          </div>
        </div>
      </div>

      <div class="footer-social">
        @include('layouts.blackbox.components.footer_social')
      </div>

    </div>

  </div>

</footer>