@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-12">
        <div class="title">
          ลงประกาศ ซื้อ ขาย ให้เช่าอสังหาริมทรัพย์
        </div>
      </div>
    </div>
  </div>

  @include('components.form_error') 

  <?php 
    echo Form::open(['id' => 'main_form','method' => 'post', 'enctype' => 'multipart/form-data']);
  ?>

  <?php
    echo Form::hidden('_model', $_formModel['modelName']);
  ?>

  <div class="form-section">

    <div class="form-row">
      <?php 
        echo Form::label('announcement_type_id', 'ประเภทของการประกาศ', array(
          'class' => 'required'
        ));
      ?>
      <div class="btn-group">
        <?php 
          foreach ($_fieldData['announcementTypes'] as $id => $type):
        ?>
          <label class="btn">
            <?php
              echo Form::radio('announcement_type_id', $id, ($defaultAnnouncementType == $id) ? true : false);
            ?>
            <div class="inner">{{$type}}</div>
          </label>
        <?php
          endforeach;
        ?>
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ชื่อสังหาริมทรัพย์ที่ต้องการประกาศ', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อสังหาริมทรัพย์ที่ต้องการประกาศ',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('real_estate_type_id', 'ประเภทอสังหาริมทรัพย์', array(
          'class' => 'required'
        ));
      ?>
      <div class="form-item-group">
        <div class="form-item-group-inner">
          <div class="row">
            <?php 
              foreach ($_fieldData['realEstateTypes'] as $id => $category):
            ?>
              <div class="col-lg-4 col-md-6 col-sm-6 col-sm-12">
                <label class="choice-box">
                  <?php
                    echo Form::radio('real_estate_type_id', $id);
                  ?>
                  <div class="inner">{{$category}}</div>
                </label>
              </div>
            <?php
              endforeach;
            ?>
          </div>
        </div>
      </div>
    </div>

    <div class="form-row">

      <div class="sub-title">รายละเอียดอสังหาริมทรัพย์</div>

      <div class="sub-form">

        <div class="sub-form-inner">

          <div class="form-row">
            <?php 
              echo Form::label('name', 'พื้นที่ใช้สอย');
            ?>
            <span class="input-addon">
              <?php
                echo Form::text('home_area[sqm]', null, array(
                  'class' => 'home-area',
                  'placeholder' => 'พื้นที่ใช้สอย',
                  'autocomplete' => 'off'
                ));
              ?>
              <span>ตารางเมตร</span>
            </span>
          </div>

          <div class="form-row">
            <?php 
              echo Form::label('name', 'พื้นที่ที่ดิน');
            ?>

            <span class="input-addon">
              <?php
                echo Form::text('land_area[rai]', null, array(
                  'id' => 'rai',
                  'class' => 'land-area',
                  'placeholder' => 'ไร่',
                  'autocomplete' => 'off'
                ));
              ?>
              <span>ไร่</span>
            </span>

            <span class="input-addon">
              <?php
                echo Form::text('land_area[ngan]', null, array(
                  'id' => 'ngan',
                  'class' => 'land-area',
                  'placeholder' => 'งาน',
                  'autocomplete' => 'off'
                ));
              ?>
              <span>งาน</span>
            </span>

            <span class="input-addon">
              <?php
                echo Form::text('land_area[wa]', null, array(
                  'id' => 'wa',
                  'class' => 'land-area',
                  'placeholder' => 'ตารางวา',
                  'autocomplete' => 'off'
                ));
              ?>
              <span>ตารางวา</span>
            </span>

            <div class="line space-top-bottom-10"></div>

            <span class="input-addon">
              <?php
                echo Form::text('land_area[sqm]', null, array(
                  'id' => 'sqm',
                  'class' => 'land-area',
                  'placeholder' => 'ตารางเมตร',
                  'autocomplete' => 'off'
                ));
              ?>
              <span>ตารางเมตร</span>
            </span>

          </div>

          <div class="form-row">
            <?php 
              echo Form::label('name', 'คุณสมบัติ');
            ?>

            <div class="input-addon-group">
              <span class="input-addon">
                <span>ห้องนอน</span>
                <?php
                  echo Form::text('indoor[bedroom]', 0, array(
                    'class' => 'indoor',
                    'placeholder' => 'ห้องนอน',
                    'autocomplete' => 'off'
                  ));
                ?>
              </span>

              <span class="input-addon">
                <span>ห้องน้ำ</span>
                <?php
                  echo Form::text('indoor[bathroom]', 0, array(
                    'class' => 'indoor',
                    'placeholder' => 'ห้องน้ำ',
                    'autocomplete' => 'off'
                  ));
                ?>
              </span>

              <span class="input-addon">
                <span>ห้องนั่งเล่น</span>
                <?php
                  echo Form::text('indoor[living_room]', 0, array(
                    'class' => 'indoor',
                    'placeholder' => 'ห้องนั่งเล่น',
                    'autocomplete' => 'off'
                  ));
                ?>
              </span>
            </div>

            <div class="input-addon-group">
              <span class="input-addon">
                <span>ห้องทำงาน</span>
                <?php
                  echo Form::text('indoor[home_office]', 0, array(
                    'class' => 'indoor',
                    'placeholder' => 'ห้องทำงาน',
                    'autocomplete' => 'off'
                  ));
                ?>
              </span>

              <span class="input-addon">
                <span>จำนวนชั้น</span>
                <?php
                  echo Form::text('indoor[floors]', 0, array(
                    'class' => 'indoor',
                    'placeholder' => 'จำนวนชั้น',
                    'autocomplete' => 'off'
                  ));
                ?>
              </span>

              <span class="input-addon">
                <span>ที่จอดรถ</span>
                <?php
                  echo Form::text('indoor[carpark]', 0, array(
                    'class' => 'indoor',
                    'placeholder' => 'ที่จอดรถ',
                    'autocomplete' => 'off'
                  ));
                ?>
              </span>
            </div>

          </div>

          <div class="form-row">

            <?php 
              echo Form::label('name', 'เฟอร์นิเจอร์');
            ?>

            <div class="btn-group">
              <label class="btn">
                <?php
                  echo Form::radio('furniture', 'e');
                ?>
                <div class="inner">ไม่มี</div>
              </label>
              <label class="btn">
                <?php
                  echo Form::radio('furniture', 's', true);
                ?>
                <div class="inner">มีบางส่วน</div>
              </label>
              <label class="btn">
                <?php
                  echo Form::radio('furniture', 'f');
                ?>
                <div class="inner">ตกแต่งครบ</div>
              </label>
            </div>

          </div>

          <div class="form-row">
            <?php 
              echo Form::label('feature', 'จุดเด่น (เลือกได้มากกว่า 1 ตัวเลือก)');
            ?>
            
            <div class="form-item-group">
              <div class="form-item-group-inner">
                <div class="row">
                  <?php 
                    foreach ($_fieldData['feature'] as $id => $feature):
                  ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-sm-12">
                      <label class="choice-box">
                        <?php
                          echo Form::checkbox('feature[]', $id);
                        ?>
                        <div class="inner"><?php echo $feature; ?></div>
                      </label>
                    </div>
                  <?php
                    endforeach;
                  ?>
                </div>
              </div>
            </div>

          </div>

          <div class="form-row">
            <?php 
              echo Form::label('facility', 'สิ่งอำนวยความสะดวก (เลือกได้มากกว่า 1 ตัวเลือก)');
            ?>
            <div class="form-item-group">
              <div class="form-item-group-inner">
                <div class="row">
                  <?php 
                    foreach ($_fieldData['facility'] as $id => $facility):
                  ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-sm-12">
                      <label class="choice-box">
                        <?php
                          echo Form::checkbox('facility[]', $id);
                        ?>
                        <div class="inner"><?php echo $facility; ?></div>
                      </label>
                    </div>
                  <?php
                    endforeach;
                  ?>
                </div>
              </div>
            </div>

          </div>

          <div class="form-row">
            <?php 
              echo Form::label('description', 'รายละเอียดอสังหาริมทรัพย์');
              echo Form::textarea('description', null, array(
                'class' => 'ckeditor'
              ));
            ?>
          </div>

        </div>

      </div>

    </div>

    <div class="form-row">
      <?php 
        echo Form::label('price', 'ราคาอสังหาริมทรัพย์', array(
          'class' => 'required'
        ));
        echo Form::text('price', null, array(
          'placeholder' => 'ราคาอสังหาริมทรัพย์',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('tagging', 'แท็กที่เกี่ยวของกับอสังหาริมทรัพย์นี้');
      ?>
      <div id="_tags" class="tag"></div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('broker', 'ตัวแทนขาย');
      ?>
      <div class="btn-group">
        <label class="btn">
          <?php
            echo Form::radio('need_broker', 1);
          ?>
          <div class="inner">ต้องการ</div>
        </label>
        <label class="btn">
          <?php
            echo Form::radio('need_broker', 0, true);
          ?>
          <div class="inner">ไม่ต้องการ</div>
        </label>
      </div>
    </div>

    <div class="form-row">

      <div class="sub-title">รูปภาพ</div>

      <div class="form-row">
        <div id="_image_group">
        </div>
      </div>

    </div>

    <div class="form-section">

      <div class="title">
        ข้อมูลการติดต่อ
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('Contact[phone_number]', 'หมายเลขโทรศัพท์');
        ?>
        <div id="phone_number_input" class="text-group">
          <div class="text-group-panel"></div>
        </div>
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('Contact[email]', 'อีเมล');
        ?>
        <div id="email_input" class="text-group">
          <div class="text-group-panel"></div>
        </div>
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('Contact[line]', 'Line ID');
        ?>
        <div id="line_id_input" class="text-group">
          <div class="text-group-panel"></div>
        </div>
      </div>

    </div>

    <div class="form-section">

      <div class="title">
        ตำแหน่งอสังหาริมทรัพย์
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('Address[province_id]', 'จังหวัด');
          echo Form::select('Address[province_id]', $_fieldData['provinces'] ,null, array(
            'id' => 'province'
          ));
        ?>
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('Address[district_id]', 'อำเภอ');
          echo Form::select('Address[district_id]', array() ,null, array(
            'id' => 'district'
          ));
        ?>
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('Address[sub_district_id]', 'ตำบล');
          echo Form::select('Address[sub_district_id]', array() , null, array(
            'id' => 'sub_district'
          ));
        ?>
      </div>

      <div class="form-row">
        <?php echo Form::label('', 'ระบุตำแหน่บนแผนที่'); ?>
        <input id="pac-input" class="controls" type="text" placeholder="Search Box">
        <div id="map"></div>
      </div>

    </div>

  </div>

  <?php
    echo Form::submit('ลงประกาศ' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){
    const images = new Images('_image_group','photo',10,'description');
    images.load();

    const map = new Map();
    map.initialize();

    const address = new Address();
    address.load();

    const tagging = new Tagging();
    tagging.load();
    @if(!empty($_oldInput['Tagging']))
      tagging.setTags({!!$_oldInput['Tagging']!!});
    @endif

    const phoneNumberInput = new TextInputList('phone_number_input','Contact[phone_number]','หมายเลขโทรศัพท์');
    phoneNumberInput.disableCreatingInput();
    @if(!empty($_oldInput['Contact']['phone_number']))
      phoneNumberInput.load({!!$_oldInput['Contact']['phone_number']!!});
    @else
      phoneNumberInput.load();
    @endif

    const emailInput = new TextInputList('email_input','Contact[email]','อีเมล');
    emailInput.disableCreatingInput();
    @if(!empty($_oldInput['Contact']['email']))
      emailInput.load({!!$_oldInput['Contact']['email']!!});
    @else
      emailInput.load();
    @endif

    const lindIdInput = new TextInputList('line_id_input','Contact[line]','Line ID');
    lindIdInput.disableCreatingInput();
    @if(!empty($_oldInput['Contact']['line']))
      lindIdInput.load({!!$_oldInput['Contact']['line']!!});
    @else
      lindIdInput.load();
    @endif

    const realEstate = new RealEstate();
    realEstate.load();

    const form = new Form();
    form.load();

  });

</script>

@stop