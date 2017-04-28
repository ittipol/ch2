@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage/product_catalog/{{request()->id}}" class="btn btn-secondary">กลับไปหน้าจัดการแคตตาล็อกสินค้า</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage/product_catalog">ไปหน้ารายการแคตตาล็อกสินค้า</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>


<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          เพิ่ม/ลบสินค้าในแคตตาล็อก
        </div>
      </div>
    </div>
  </div>

  @if(Session::has('product_catalog_added'))
    <div class="secondary-message-box success space-bottom-30">
      <div class="secondary-message-box-inner">
        <h3>แคตตาล็อกถูกสร้างแล้ว</h3>
        <p>ลูกค้าสามารถเข้ามาดูสินค้าในแคตตาล็อกนี้ได้แล้ว</p>
      </div>
    </div>

    <div class="secondary-message-box info space-bottom-30">
      <div class="secondary-message-box-inner">
        <h3>ยังไม่มีสินค้าในแคตตาล็อก</h3>
        <p>*** เพิ่มสินค้าลงในแคตตาล็อก เพื่อเป็นการจัดกลุ่ม ประเภท หรือรูปแบบของสินค้าที่มีความใกล้เคียงกันหรือจะเป็นการกำหนดตามที่คุณต้องการ</p>
        <p>*** เมื่อเพิ่มสินค้าในแคตตาล็อกแล้ว ลูกค้าสามารถเข้ามาดูสินค้าผ่านแคตตาล็อกที่คุณสร้างไว้ได้</p>
      </div>
    </div>
  @endif

  <h5>กำลังจัดการแคตตาล็อก</h5>
  <h4>{{$_formData['name']}}</h4>

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
        echo Form::label('', 'รายการสินค้า', array(
          'class' => 'required'
        ));
      ?>
      <div class="form-item-group">
        <div class="form-item-group-inner">
          <div class="row">
            <?php 
              foreach ($_fieldData['products'] as $id => $name):
            ?>
              <div class="col-sm-12">
                <label class="choice-box">
                  <?php
                    echo Form::checkbox('ProductToProductCatalog[product_id][]', $id);
                  ?>
                  <div class="inner">{{$name}}</div>
                </label>
              </div>
            <?php
              endforeach;
            ?>
          </div>
        </div>
      </div>
    </div>

  </div>

  <?php
    echo Form::submit('บันทึก', array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const form = new Form();
    form.load();
    
  });    
</script>

@stop