<div class="shop-top-haeder-wrapper">

  <div class="shop-top-header shop-default-cover-bg">

    @if(!empty($_shop_cover))
    <div id="shop_cover" class="shop-cover" style="background-image: url({{$_shop_cover}});"></div>
    @else
    <div id="shop_cover" class="shop-cover"></div>
    @endif

    <div class="shop-overlap-panel">
      <div class="shop-logo-panel">
        <div class="shop-logo-outer">
          @if(!empty($_shop_profileImage))
          <div id="shop_logo" class="shop-logo" style="background-image: url({{$_shop_profileImage}});"></div>
          @else
          <div id="shop_logo" class="shop-logo"></div>
          @endif
        </div>

        @if(!empty($_shop_permission['edit']))
        <div class="additional-option icon float-button shop-profile-image-edit-button">
          <img src="/images/icons/edit-blue.png">
          <div class="additional-option-content">
            <label class="item-row" id="edit_shop_profile_image_button">
              อัพโหลดรูปภาพ
            </label>
            @if(!empty($_shop_profileImage))
            <a class="item-row">
              ลบรุปภาพโปรไฟล์
            </a>
            @endif
          </div>
        </div>
        @endif

        <a id="accept_shop_profile_image_button" class="icon-button-round right-icon float-button profile-image-accept button"></a>
        <a id="cancel_shop_profile_image_button" class="icon-button-round close-icon float-button profile-image-cancel button"></a>

      </div>

      <div class="shop-info">
        <h3>{{$_shop_name}}</h3>
        @if(!empty($_shop_short_description) && ($_shop_short_description != '-'))
        <p class="shop-description">{{$_shop_short_description}}</p>
        @endif
      </div>
    </div>

    @if(!empty($_shop_permission['edit']))
    <div class="additional-option button float-button shop-cover-edit-button">
      <img src="/images/icons/edit-blue.png">
      รูปภาพหน้าปก
      <div class="additional-option-content">
        <label class="item-row" id="edit_shop_cover_button">
          อัพโหลดรูปภาพ
        </label>
        @if(!empty($_shop_cover))
        <a class="item-row">
          ลบรุปภาพหน้าปก
        </a>
        @endif
      </div>
    </div>
    @endif

    <a id="accept_shop_cover_button" class="icon-button-round right-icon float-button cover-accept button"></a>
    <a id="cancel_shop_cover_button" class="icon-button-round close-icon float-button cover-cancel button"></a>

  </div>


</div>

<nav class="shop-main-nav">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="nav-group clearfix">
          <a class="nav">หน้าแรก</a>
          <a class="nav">เกี่ยวกับ</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<script type="text/javascript">

  @if(!empty($_shop_permission['edit']))
  $(document).ready(function(){
    const cover = new ProfileImage('{{ csrf_token() }}','Shop',{{$_shop_id}},'cover');
    cover.load();
    cover.setElem('edit_shop_cover_button','shop_cover','accept_shop_cover_button','cancel_shop_cover_button');
  
    const profileImage = new ProfileImage('{{ csrf_token() }}','Shop',{{$_shop_id}},'profile-image');
    profileImage.load();
    profileImage.setElem('edit_shop_profile_image_button','shop_logo','accept_shop_profile_image_button','cancel_shop_profile_image_button');
  });
  @endif

</script>