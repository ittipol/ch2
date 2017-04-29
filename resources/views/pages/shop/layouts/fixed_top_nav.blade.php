<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}" class="btn btn-secondary">หน้าหลักร้านค้า</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}about">เกี่ยวกับ</a>
              <a href="{{request()->get('shopUrl')}}product">สินค้า</a>
              <a href="{{request()->get('shopUrl')}}product_catalog">แคตตาล็อกสินค้า</a>
              <a href="{{request()->get('shopUrl')}}job">ประกาศงาน</a>
              <a href="{{request()->get('shopUrl')}}advertising">โฆษณา</a>
              <a href="{{request()->get('shopUrl')}}branch">สาขา</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>