@extends('layouts.blackbox.main')
@section('content')

<div class="container">
  <div class="list-empty-message text-center space-top-40">
    <img src="/images/common/resume.png">
    <div>
      <h3>เรซูเม่</h3>
      <p>สร้างเรซูเม่ของคุณ เพื่อใช้ในการสมัครสมาชิกผ่านเว็บไชต์ ซึ่งจะทำให้เกิดความสะดวกและรวดเร็วในการสมัครงาน และยังสามารถแก้ไชเรซูเม่ของคุณได้ตลอดเวลาตามที่คุณต้องการ</p>
      <?php
        echo Form::open(['method' => 'post', 'enctype' => 'multipart/form-data']);

        echo Form::submit('สร้างเรซูเม่' , array(
          'class' => 'button'
        ));

        echo Form::close();
      ?>
    </div>
  </div>
</div>

@stop