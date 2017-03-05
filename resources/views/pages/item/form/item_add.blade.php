@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper">
  <div class="top-header">
    <h2>ลงประกาศ ซื้อ ขาย สินค้า</h2>
  </div>
</div>

<div class="container">

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
        @foreach ($_fieldData['announcementTypes'] as $id => $type)
          <label class="btn">
            <?php
              echo Form::radio('announcement_type_id', $id, ($defaultAnnouncementType == $id) ? true : false);
            ?>
            <div class="inner">{{$type}}</div>
          </label>
        @endforeach
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ชื่อสินค้าที่ต้องการประกาศ', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อสินค้าที่ต้องการประกาศ',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('item_category_id', 'หมวดหมู่หลักสินค้า', array(
          'class' => 'required'
        ));
      ?>
      <div class="form-item-group">
        <div class="form-item-group-inner">
          <div class="row">
            @foreach ($_fieldData['itemCategories'] as $id => $category)
            <div class="col-lg-4 col-sm-6 col-sm-12">
              <label class="choice-box">
                <?php
                  echo Form::radio('ItemToCategory[item_category_id]', $id);
                ?> 
                <div class="inner"><?php echo $category; ?></div>
              </label>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดของสินค้า');
        echo Form::textarea('description', null, array(
          'class' => 'ckeditor'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('used', 'สภาพสินค้า');
      ?>
      <div class="btn-group">
        <label class="btn">
          <?php
            echo Form::radio('used', 0);
          ?>
          <div class="inner">สินค้าใหม่</div>
        </label>
        <label class="btn">
          <?php
            echo Form::radio('used', 1, true);
          ?>
          <div class="inner">สินค้ามือสอง</div>
        </label>
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('price', 'ราคาสินค้า', array(
          'class' => 'required'
        ));
        echo Form::text('price', null, array(
          'placeholder' => 'ราคาสินค้า',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('tagging', 'แท็กที่เกี่ยวของกับสินค้าและการประกาศนี้');
      ?>
      <div id="_tags" class="tag"></div>

    </div>

    <div class="form-row">

      <div class="sub-title">รูปภาพ</div>

      <div class="form-row">
        <div id="_image_group"></div>
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
        ตำแหน่งสินค้า
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

    const form = new Form();
    form.load();

  });

</script>

@stop