<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage/product/{{request()->id}}" class="btn btn-secondary">กลับไปหน้าจัดการสินค้า</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage/product">ไปหน้ารายการสินค้า</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>