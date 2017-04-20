@extends('layouts.default.main')
@section('content')

<div class="login-panel">

  <div class="login-form-box">

    <div class="login-form-inner">
      <h3><a class="logo" href="{{URL::to('/')}}">CHONBURI SQUARE</a></h3>

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

      <div class="line"></div>

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
          <?php
            echo Form::checkbox('remember', 1);
            echo Form::label('remember', 'จดจำไว้ในระบบ');
          ?>
        </div>

        <div>
          <?php
            echo Form::submit('เข้าสู่ระบบ', array(
              'class' => 'button wide-button'
            ));
          ?>
        </div>

        <div class="line space-top-bottom-10"></div>

        <a href="#" class="fb-button">
          <img src="/images/common/fb-logo.png">
          เข้าสู่ระบบด้วย Facebook
        </a>

        <h4 class="text-center">ไม่ใช่สมาชิก <a href="{{URL::to('register')}}">สมัครสมาชิก</a></h4>

        <?php
          echo Form::close();
        ?>
      </div>
    </div>

  </div>

</div>

<script type="text/javascript">

  $('body').css('background-color','#999');

  class LoginPage {
    constructor() {}

    init() {

      let w = window.innerWidth;

      $('body').css('height',window.innerHeight);

      if(w > 992) {

        $('body').addClass('login-bg');

        let background = new Image();
        background.src = '/images/login.jpg';

        let windowWidth = window.innerWidth;
        // let windowHeight = window.innerHeight;

        background.onload = function() {
          // let bgHeight = background.height;
          let bgWidth = background.width;
          // let ratio = background.width / background.height;

          // if (bgWidth > windowWidth) {
          //   $('body').css('background-size','auto 100%');
          // }else{
          //   $('body').css('background-size','100% auto');
          // }

          $('body').css('background-size','cover');

          $('body').css({
            'background-image':'url('+background.src+')',
            'background-position': 'center'
          });

        }

      }else{

        // $('.login-panel').removeClass('login-panel');

        // $('.login-form-box').addClass('login-form').removeClass('login-form-box').css({
        //   'height':window.innerHeight,
        // });

        // $('.login-form-box').css({
        //   'height':window.innerHeight,
        // });

      }

    }

  }

  $(document).ready(function(){
    loginPage = new LoginPage();
    loginPage.init();
  });

</script>
@stop