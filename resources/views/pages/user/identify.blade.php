@extends('layouts.default.main')
@section('content')

@include('layouts.blackbox.components.common')

<div class="login-logo">
  <a href="{{URL::to('/')}}"><img src="/images/ss_logo/logo-login.png"></a>
</div>

<div class="identify-form">

  <?php
    echo Form::open(['method' => 'post', 'id' => 'login_form']);
  ?>

    <h4>ลืมรหัสผ่าน</h4>
    <p>โปรดป้อนอีเมลของคุณเพื่อร้องขอการรีเซ็ตรหัสผ่าน</p>

    <div class="form-section">
      <div class="form-row">
        <input type="text" id="email" name="email" placeholder="อีเมล" autocomplete="off">
      </div>
    </div>

    <div>
      <?php
        echo Form::submit('ส่งคำร้องไปยังอีเมล', array(
          'class' => 'button wide-button'
        ));
      ?>
    </div>

    <h5 class="text-center space-top-20"><a href="{{URL::to('login')}}">เข้าสู่ระบบ</a> | <a href="{{URL::to('register')}}">สมัครสมาชิก</a></h5>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  class IdentifyForm {

    constructor() {}

    load() {
      this.bind();
    }

    bind() {

      $('form').submit(function(){

        if($('#email').val() == '') {

          const notificationBottom = new NotificationBottom();
          notificationBottom.setTitle('โปรดป้อนอีเมลของคุณเพื่อร้องขอการรีเซ็ตรหัสผ่าน');
          notificationBottom.setType('error');
          notificationBottom.display();

          return false;
        }

        $(this).find('input[type="submit"]').prop('disabled','disabled').addClass('disabled');

        $('#loading_icon').addClass('display');
        $('.global-overlay').addClass('isvisible');

      });

    }

  }

  $(document).ready(function(){

    const identifyForm = new IdentifyForm();
    identifyForm.load();

  });

</script>

@stop