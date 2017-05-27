<footer>

  <div class="container-fuild">

    <div class="footer">

      <div class="row">

        <div class="site-map col-xs-12">

          <div class="row">

            <div class="col-md-2 col-xs-12">
              <h4 class="site-map-title">บริษัทและร้านค้า</h4>
              <div class="site-map-content">
                <div class="site-map-link">
                  <a href="{{URL::to('shop/create')}}">สร้างบริษัทหรือร้านค้า</a>
                </div>
                <div class="site-map-link">
                  <a href="{{URL::to('product')}}">สินค้า</a>
                </div>
                <div class="site-map-link">
                  <a href="{{URL::to('job')}}">งาน</a>
                </div>
              </div>
            </div>

            <div class="col-md-2 col-xs-12">
              <h4 class="site-map-title">การช่วยเหลือ</h4>
              <div class="site-map-content">
                <div class="site-map-link">
                  <a href="{{URL::to('manual')}}">วิธีการใช้งาน</a>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>

      <div class="row">
        <div class="col-xs-6">
          <div class="footer-content">
            <h4 class="no-margin">{{date('Y')}} Sunday Square</h4>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="footer-content pull-right">
            <a href="">
              <img class="social-logo" src="/images/common/fb-logo.png">
            </a>
            <a href="">
              <img class="social-logo" src="/images/common/yt-logo.png">
            </a>
          </div>
        </div>
      </div>

    </div>

  </div>

</footer>