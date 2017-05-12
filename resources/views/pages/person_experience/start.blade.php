@extends('layouts.blackbox.main')
@section('content')

<div class="container">
  <div class="list-empty-message text-center space-top-40">
    <img src="/images/common/resume.png">
    <div>
      <h3>เรซูเม่</h3>
      <p>เรซูเม่จะทำหน้าที่บ่งบอกตัวต้นและความสามารถของคุณรวมถึงช่วยเพิ่มโอกาสในการทำงานและประสบความสำเร็จในสายอาชีพของคุณ</p>
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