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
                  <a href="{{URL::to('manual/seller')}}">วิธีการใช้งาน</a>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>  

      <div class="row space-bottom-40">
        <div class="col-md-3 col-xs-12">
          <h5><strong>ติดต่อเรา</strong></h5>
          <div class="footer-content">
            <img src="/images/common/fb-logo.png">
            <span>Sunday Square</span>
          </div>
        </div>
      </div>

      <h4>{{date('Y')}} Sunday Square</h4>

    </div>

  </div>

</footer>