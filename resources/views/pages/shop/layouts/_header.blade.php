<div class="shop-header shop-default-cover">
  <div class="shop-cover" style="background-image: url({{$_shop_cover}});"></div>
  <div class="contain-fluid">
    <div class="shop-header-overlay clearfix">
      <div class="row">
        <div class="col-md-12 col-lg-9">
          <div class="shop-header-info clearfix">
            <div class="shop-logo">
              <div class="logo" style="background-image: url({{$_shop_profileImage}});"></div>
            </div>
            <section class="shop-description">
              <h2><?php echo $_modelData['name']; ?></h2>
              <p><?php echo $_modelData['_short_description']; ?></p>
            </section>
          </div>
        </div>
        <div class="col-md-12 col-lg-3">
          <div class="shop-header-secondary-info">

            @if(!empty($entity['OfficeHour']))
            <!-- <div class="additional-option triangle working-time-status <?php echo $entity['OfficeHour']['status']['name']; ?>">
              <?php echo $entity['OfficeHour']['status']['text']; ?>
              <div class="additional-option-content">
                <?php foreach ($entity['OfficeHour']['workingTime'] as $workingTime): ?>
                <span><?php echo $workingTime['day'].' '.$workingTime['workingTime']; ?></span>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="line space-top-bottom-20"></div> -->
            @endif

            @if(!empty($_modelData['Address']['fullAddress']))
            <div class="shop-info">
              <h4>ที่อยู่</h4>
              <div>
                {{$_modelData['Address']['fullAddress']}}
              </div>
            </div>
            @endif

          </div>
        </div>
      </div>
    </div>
  </div>
</div>