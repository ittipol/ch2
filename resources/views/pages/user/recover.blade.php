@extends('layouts.default.main')
@section('content')

@include('layouts.blackbox.components.common')

<div class="login-logo">
  <a href="{{URL::to('/')}}"><img src="/images/ss_logo/logo-login.png"></a>
</div>

<div class="fixed-center-form">

  @include('components.form_error')

  <?php
    echo Form::open(['method' => 'post', 'id' => 'recovery_form']);
  ?>

  <h4>กำหนดรหัสผ่านของคุณใหม่</h4>
  
  <div class="form-section">
    <div class="form-row">
      <input type="password" name="password" placeholder="รหัสผ่านใหม่ (อย่างน้อย 4 อักขระ)" autocomplete="off">
    </div>
    <div class="form-row">
      <input type="password" name="password_confirmation" placeholder="ป้อนรหัสผ่านใหม่อีกครั้ง" autocomplete="off">
    </div>
  </div>

  {{ Form::hidden('user', $email) }}
  {{ Form::hidden('key', $key) }}

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

  class RecoveryForm {

    constructor() {}

    load() {
      this.bind();
    }

    bind() {

      $('form#recovery_form').submit(function(){

        $(this).find('input[type="submit"]').prop('disabled','disabled').addClass('disabled');

        $('#loading_icon').addClass('display');
        $('.global-overlay').addClass('isvisible');

      });

    }

  }

  $(document).ready(function(){

    const recoveryForm = new RecoveryForm();
    recoveryForm.load();

  });

</script>

@stop