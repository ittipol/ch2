@extends('layouts.default.main')
@section('content')

<div class="register-wrapper">
	<div class="header-container">
		<h3><a class="logo" href="{{URL::to('/')}}">SUNDAY SQUARE</a></h3>
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

			<h4 class="text-center">เป็นสมาชิกแล้ว <a href="{{URL::to('login')}}">ลงชื่อเข้าใช้</a></h4>

		</div>

		<?php
			echo Form::close();
		?>

	</div>
</div>

	<script type="text/javascript">

    // class RegisterPage {
    //   constructor() {}

    //   init() {}

    // }

    $(document).ready(function(){
      // registerPage = new RegisterPage();
      // registerPage.init();

      $('body').css('background-color','rgb(29,66,133)');

    });
  </script>
  
@stop