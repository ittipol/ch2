<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage" class="btn btn-secondary">ภาพรวมร้านค้า</a>
          <button class="btn btn-secondary additional-option">
            จัดการร้านค้า...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage/product">สินค้า</a>
              <a href="{{request()->get('shopUrl')}}manage/product_catalog">แคตตาล็อกสินค้า</a>
              <a href="{{request()->get('shopUrl')}}manage/job">ประกาศงาน</a>
              <a href="{{request()->get('shopUrl')}}manage/advertising">โฆษณา</a>
              <a href="{{request()->get('shopUrl')}}manage/branch">สาขา</a>
              <a href="{{request()->get('shopUrl')}}setting">ข้อมูลร้านค้า</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>