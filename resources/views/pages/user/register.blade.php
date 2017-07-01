@extends('layouts.default.main')
@section('content')

<div class="register-bg"></div>

<div class="register-wrapper">
	<div class="header-container">
		<a class="logo space-bottom-20" href="{{URL::to('/')}}"><img src="/images/ss_logo/logo-register.png"></a>
    <p>สมัครสมาชิก</p>
	</div>

	<div class="register-form">

		@include('components.form_error') 

		<?php
			echo Form::open(['url' => 'register', 'method' => 'post', 'enctype' => 'multipart/form-data']);
		?>
		
		<div class="register-form-inner">
		
			<div class="form-row">
				<input type="text" name="Person[name]" placeholder="ชื่อ นามสกุล" autocomplete="off">
			</div>
			<div class="form-row">
				<input type="text" name="email" placeholder="อีเมล" autocomplete="off">
			</div>
			<div class="form-row">
				<input type="password" name="password" placeholder="รหัสผ่าน (อย่างน้อย 4 อักขระ)" autocomplete="off">
			</div>
			<div class="form-row">
				<input type="password" name="password_confirmation" placeholder="ป้อนรหัสผ่านอีกครั้ง" autocomplete="off">
			</div>

			<?php
				echo Form::submit('สมัครสมาชิก', array(
					'class' => 'button wide-button'
				));
			?>

			<h5 class="text-center space-top-20">เป็นสมาชิกแล้ว <a href="{{URL::to('login')}}">ลงชื่อเข้าใช้</a></h5>

		</div>

		<?php
			echo Form::close();
		?>

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