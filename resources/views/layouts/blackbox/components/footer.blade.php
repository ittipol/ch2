<footer>

  <div class="container-fuild">

    <div class="footer">

      <div class="row">
        <div class="col-xs-12">
          <h3>ชุมชน</h3>
          <!-- <p>เชื่อมต่อคุณหรือธุรกิจของคุณกับชุมชนและผู้คนในชลบุรี เพื่อสะดวกต่อการเข้าถึงบิษัท ร้านค้า สินค้า แบรนด์ งานบริการ ตำแหน่งงาน และอื่นๆ อีกมากมาย</p> -->
        </div>

        <div class="site-map col-xs-12">

          <div class="row">

            <div class="col-md-3">
              <h4 class="site-map-title">บริษัทและร้านค้า</h4>
              <div class="site-map-content">
                <div class="site-map-link">
                  <a href="{{URL::to('community/shop/create')}}">เพิ่มร้านค้าของคุณ</a>
                </div>
                <div class="site-map-link">
                  <a href="{{URL::to('product/shelf')}}">สินค้าจากร้านค้า</a>
                </div>
                <div class="site-map-link">
                  <a href="{{URL::to('job/board')}}">ประกาศงาน</a>
                </div>
                <div class="site-map-link">
                  <a href="{{URL::to('advertising/board')}}">โฆษณาจากบริษัทและร้านค้า</a>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <h4 class="site-map-title">บอร์ดประกาศ</h4>
              <div class="site-map-content">
                <div class="site-map-link">
                  <a href="{{URL::to('item/board')}}">ประกาศซื้อ-เช่า-ขายสินค้า</a>
                </div>
                <div class="site-map-link">
                  <a href="{{URL::to('real-estate/board')}}">ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์</a>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <h4 class="site-map-title">ประวัติการทำงาน</h4>
              <div class="site-map-content">
                <div class="site-map-link">
                  <a href="{{URL::to('experience/profile/list')}}">ประวัติการทำงานบุคคล</a>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <h4 class="site-map-title">ฟรีแลนซ์</h4>
              <div class="site-map-content">
                <div class="site-map-link">
                  <a href="{{URL::to('freelance/board')}}">งานฟรีแลนซ์</a>
                </div>
              </div>
            </div>

          </div>

        </div>
        
      </div>

      <h4>{{date('Y')}} CHONBURI SQUARE</h4>

      <div>
        <span>ติดต่อเรา</span>
      </div>

    </div>

  </div>

</footer>