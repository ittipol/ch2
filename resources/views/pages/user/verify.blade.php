@extends('layouts.default.main')
@section('content')

@include('layouts.blackbox.components.common')

<div class="login-logo">
  <a href="{{URL::to('/')}}"><img src="/images/ss_logo/logo-login.png"></a>
</div>

<div class="verify-form">

  <?php
    echo Form::open(['method' => 'post', 'id' => 'verify_form']);
  ?>

  <h4>รีเซ็ตรหัสผ่าน</h4>
  <p>โปรดป้อนรหัสผ่านใหม่</p>

  <div class="form-section">
    <div class="form-row">
      <input type="password" name="password" placeholder="รหัสผ่าน (อย่างน้อย 4 อักขระ)" autocomplete="off">
    </div>
    <div class="form-row">
      <input type="password" name="password_confirmation" placeholder="ป้อนรหัสผ่านอีกครั้ง" autocomplete="off">
    </div>
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