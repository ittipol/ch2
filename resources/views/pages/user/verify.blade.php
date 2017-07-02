@extends('layouts.default.main')
@section('content')

@include('layouts.blackbox.components.common')

<div class="login-logo">
  <a href="{{URL::to('/')}}"><img src="/images/ss_logo/logo-login.png"></a>
</div>

<div class="fixed-center-form">

  <?php
    echo Form::open(['method' => 'post', 'id' => 'verify_form']);
  ?>

  <h4>รีเซ็ตรหัสผ่าน</h4>

  <p>ป้อนอีเมลที่เป็นบัญชีของคุณ</p>
  <div class="form-section">
    <div class="form-row">
      <input type="text" name="email" placeholder="อีเมล" autocomplete="off">
    </div>
  </div>

  <p>ป้อนรหัสผ่านใหม่</p>

  <div class="form-section">
    <div class="form-row">
      <input type="password" name="password" placeholder="รหัสผ่านใหม่ (อย่างน้อย 4 อักขระ)" autocomplete="off">
    </div>
    <div class="form-row">
      <input type="password" name="password_confirmation" placeholder="ป้อนรหัสผ่านใหม่อีกครั้ง" autocomplete="off">
    </div>
  </div>

  <p>รหัสตรวจสอบ</p>

  <div class="form-section">
    <div class="form-row">
      <input type="text" value="a68751a61862bed26bc3ca034b24b26b111d292514d4052b9fe687e334325e28" autocomplete="off" disabled>
    </div>
  </div>

  <div>
      <?php
        echo Form::submit('ตกลง', array(
          'class' => 'button wide-button'
        ));
      ?>
    </div>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  class VerifyForm {

    constructor() {}

    load() {
      this.bind();
    }

    bind() {

      $('form#verify_form').submit(function(){

        $(this).find('input[type="submit"]').prop('disabled','disabled').addClass('disabled');

        $('#loading_icon').addClass('display');
        $('.global-overlay').addClass('isvisible');

        return false;

      });

    }

  }

  $(document).ready(function(){

    const verifyForm = new VerifyForm();
    verifyForm.load();

  });

</script>

@stop