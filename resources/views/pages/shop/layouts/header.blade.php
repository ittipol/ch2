<div class="container">

  <div class="shop-top-haeder-wrapper">

    <div class="shop-top-header shop-default-cover-bg">

      <div class="cover-size-info">1140 x 400</div>

      @if(!empty($_shop_cover))
      <div id="shop_cover" class="shop-cover" style="background-image: url({{$_shop_cover}});"></div>
      @else
      <div id="shop_cover" class="shop-cover"></div>
      @endif

      @if(!empty($_shop_permission['edit']))
      <div class="additional-option button float-button shop-cover-edit-button">
        <img src="/images/icons/edit-blue.png">
        ภาพหน้าปก
        <div class="additional-option-content">
          <label class="item-row" id="edit_shop_cover_button">
            อัพโหลดรูปภาพ
          </label>
          <a id="remove_shop_cover_button" class="item-row">
            ลบรุปภาพหน้าปก
          </a>
        </div>
      </div>
      @endif

      <a id="accept_shop_cover_button" class="icon-button-round right-icon float-button cover-accept button"></a>
      <a id="cancel_shop_cover_button" class="icon-button-round close-icon float-button cover-cancel button"></a>

    </div>

    <div class="shop-main-info">

      <div class="shop-main-info-inner clearfix">

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
              <a id="remove_shop_profile_image_button" class="item-row">
                ลบรุปภาพโปรไฟล์
              </a>
            </div>
          </div>
          @endif

          <a id="accept_shop_profile_image_button" class="icon-button-round right-icon float-button profile-image-accept button"></a>
          <a id="cancel_shop_profile_image_button" class="icon-button-round close-icon float-button profile-image-cancel button"></a>

        </div>

        <div class="shop-info">

          <h3>{{$_shop_name}}</h3>

          @if(!empty($_shop_open_hours))
          <div class="shop-open-hours">
            <div class="additional-option after-text-icon black-color shop-open-sign {{$_shop_open_hours['status']}}">
              {{$_shop_open_hours['text']}}
              <div class="additional-option-content">
                <div class="shop-time-table-wrapper">
                @foreach($_shop_open_hours['timeTable'] as $time)
                  <div class="shop-time-table clearfix">
                    <div class="shop-time-table-day pull-left">{{$time['day']}}</div>
                    <div class="shop-time-table-time pull-left">{{$time['openHour']}}</div>
                  </div>
                @endforeach
                </div>
              </div>
            </div>
          </div>
          @endif

          @if(!empty($_shop_short_description) && ($_shop_short_description != '-'))
          <p class="shop-description">{{$_shop_short_description}}</p>
          @endif

        </div>

      </div>

    </div>

  </div>

</div>

<script type="text/javascript">

  @if(!empty($_shop_permission['edit']))
  $(document).ready(function(){
    const cover = new ProfileImage('{{ csrf_token() }}','Shop',{{$_shop_id}},'cover');
    cover.load();
    cover.setElem('edit_shop_cover_button','shop_cover','accept_shop_cover_button','cancel_shop_cover_button','remove_shop_cover_button');
  
    const profileImage = new ProfileImage('{{ csrf_token() }}','Shop',{{$_shop_id}},'profile-image');
    profileImage.load();
    profileImage.setElem('edit_shop_profile_image_button','shop_logo','accept_shop_profile_image_button','cancel_shop_profile_image_button','remove_shop_profile_image_button');
  });
  @endif

</script>