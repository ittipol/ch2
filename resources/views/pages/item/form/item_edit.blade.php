@extends('layouts.blackbox.main')
@section('content')

<div class="container">
  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">แก้ไขประกาศสินค้า</div>
      </div>
    </div>
  </div>
</div>

<div class="container">

  @include('components.form_error') 

  <?php 
    echo Form::model($_formData, [
      'id' => 'main_form',
      'method' => 'PATCH',
      'enctype' => 'multipart/form-data'
    ]);
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
              echo Form::radio('announcement_type_id', $id);
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
            <?php 
              foreach ($_fieldData['itemCategories'] as $id => $category):
            ?>
              <div class="col-lg-4 col-sm-6 col-sm-12">
                <label class="choice-box">
                  <?php
                    echo Form::radio('ItemToCategory[item_category_id]', $id);
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
      <?php 
        echo Form::label('description', 'รายละเอียดของสินค้า');
        echo Form::textarea('description');
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
          <a href="javascript:void(0);" class="text-add">เพิ่ม +</a>
        </div>
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('Contact[email]', 'อีเมล');
        ?>
        <div id="email_input" class="text-group">
          <div class="text-group-panel"></div>
          <a href="javascript:void(0);" class="text-add">เพิ่ม +</a>
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

    </div>

  </div>

  <?php
    echo Form::submit('บันทึก' , array(
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
    images.load({!!$_formData['Image']!!});

    const tagging = new Tagging();
    tagging.load({!!$_formData['Tagging']!!});
    
    const form = new Form();
    form.load();

    const address = new Address();
    address.setDistrictId({{$_formData['Address']['district_id']}});
    address.load();

    const phoneNumberInput = new TextInputList('phone_number_input','Contact[phone_number]','หมายเลขโทรศัพท์');
    // phoneNumberInput.disableCreatingInput();
    phoneNumberInput.load({!!$_formData['Contact']['phone_number']!!});

    const emailInput = new TextInputList('email_input','Contact[email]','อีเมล');
    // emailInput.disableCreatingInput();
    emailInput.load({!!$_formData['Contact']['email']!!});

  });

</script>

@stop