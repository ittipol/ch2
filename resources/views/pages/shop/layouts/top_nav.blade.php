<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <button class="btn btn-secondary additional-option">
            จัดการ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage/product">สินค้า</a>
              <a href="{{request()->get('shopUrl')}}manage/job">ประกาศงาน</a>
              <a href="{{request()->get('shopUrl')}}manage/advertising">การโฆษณา</a>
              <a href="{{request()->get('shopUrl')}}manage/branch">สาขา</a>
            </div>
          </button>
          <a href="{{request()->get('shopUrl')}}setting" class="btn btn-secondary">ข้อมูลร้านค้า</a>
        </div>

      </div>
    </div>
  </div>
</div>