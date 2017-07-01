@extends('layouts.default.main')
@section('content')

<div class="login-logo">
  <a href="{{URL::to('/')}}"><img src="/images/ss_logo/logo-login.png"></a>
</div>

<div id="login" class="login-wrapper">

  <div class="login-banner-fixed" style="background-image: url(/images/banners/login/{{$bannerImage}});"></div>

  <div class="login-panel">
    <div class="login-form-box">

      <div class="login-form-inner">
        <h3><a class="logo">เข้าสู่ระบบ</a></h3>

        <p>เข้าถึงเนื้อหาทั้งหมดด้วยการเข้าสู่ระบบ</p>

        @if(!empty($errors->all()))
        <div class="form-error-messages space-bottom-20">
          <div class="form-error-messages-inner">
            <div class="error-message-group">
              <?php foreach ($errors->all() as $message) { ?>
                <div class="error-messages"><?php echo $message; ?></div>
              <?php } ?>
            </div>
          </div>
        </div>
        @endif

        <div class="login-form-main">

          <?php
            echo Form::open(['method' => 'post', 'id' => 'login_form']);
          ?>

          <div class="form-row">
            <input type="text" name="email" placeholder="อีเมล" autocomplete="off">
          </div>

          <div class="form-row">
            <input type="password" name="password" placeholder="รหัสผ่าน">
          </div>

          <div class="form-row">
            <label class="choice-box">
              <?php
                echo Form::checkbox('remember', 1);
              ?>
              <div class="inner">จดจำไว้ในระบบ</div>
            </label>
          </div>

          <div>
            <?php
              echo Form::submit('เข้าสู่ระบบ', array(
                'class' => 'button wide-button'
              ));
            ?>
          </div>

          <h5 class="text-center space-top-20">ไม่ใช่สมาชิก <a href="{{URL::to('register')}}">สมัครสมาชิก</a> | <a href="{{URL::to('identify')}}">ลืมรหัสผ่าน</a></h5>

          <?php
            echo Form::close();
          ?>
        </div>
      </div>

    </div>
  </div>

  <div class="login-bl-fixed">
    <a href="{{URL::to('manual')}}" class="button">
      <img src="/images/icons/document-header.png">
      วิธีการใช้งาน
    </a>
  </div>

  <div class="login-social">
    <div class="clearfix">
      @include('layouts.blackbox.components.footer_social')
    </div>
  </div>

</div>

@stop