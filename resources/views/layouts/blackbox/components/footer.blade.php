<footer>

  <div class="container-fuild">

    <div class="footer-wrapper">

      <div class="container">

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
                    <a href="{{URL::to('shop')}}">บริษัทหรือร้านค้า</a>
                  </div>
                </div>
              </div>

              <div class="col-md-2 col-xs-12">
                <h4 class="footer-title">สินค้า</h4>
                <div class="footer-content">
                  <div class="footer-link">
                    <a href="{{URL::to('product')}}">สินค้าจากบริษัทและร้านค้า</a>
                  </div>
                  <div class="footer-link">
                    <a href="{{URL::to('product/category')}}">หมวดสินค้า</a>
                  </div>
                </div>
              </div>

              <div class="col-md-2 col-xs-12">
                <h4 class="footer-title">งาน</h4>
                <div class="footer-content">
                  <div class="footer-link">
                    <a href="{{URL::to('job')}}">งานจากบริษัทและร้านค้า</a>
                  </div>
                  <div class="footer-link">
                    <a href="{{URL::to('resume')}}">เรซูเม่ส่วนตัว</a>
                  </div>
                </div>
              </div>

              <div class="col-md-2 col-xs-12">
                <h4 class="footer-title">โฆษณา</h4>
                <div class="footer-content">
                  <div class="footer-link">
                    <a href="{{URL::to('advertising')}}">โฆษณาจากบริษัทและร้านค้า</a>
                  </div>
                </div>
              </div>

              <div class="col-md-2 col-xs-12">
                <h4 class="footer-title">เกี่ยวกับ</h4>
                <div class="footer-content">
                  <div class="footer-link">
                    <a href="{{URL::to('term_condition')}}">เงื่อนไขและข้อตกลง</a>
                  </div>
                  <div class="footer-link">
                    <a href="{{URL::to('manual')}}">วิธีการใช้งาน</a>
                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>

      </div>

      <div class="line grey space-top-bottom-30"></div>

      <div class="container">

        <div class="row">
          <div class="col-xs-6">
            <div class="footer-content">
              <img src="/images/logo3.png">
            </div>
          </div>
        </div>

      </div>

      <div class="footer-social">
        @include('layouts.blackbox.components.footer_social')
      </div>

    </div>

  </div>

</footer>