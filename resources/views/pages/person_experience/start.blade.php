@extends('layouts.blackbox.main')
@section('content')

<div class="container">
  <div class="list-empty-message text-center space-top-40">
    <img class="space-bottom-20" src="/images/common/career.png">
    <div>
      <h3>ประสบการณ์การทำงานของคุณ</h3>
      <p>ยังไม่มีประสบการณ์การทำงาน เพิ่มประสบการณ์การทำงานเพื่อเพิ่มโอกาสในการทำงานและประสบความสำเร็จในสายอาชีพของคุณ</p>
      <?php
        echo Form::open(['method' => 'post', 'enctype' => 'multipart/form-data']);

        echo Form::submit('เพิ่มประสบการณ์การทำงาน' , array(
          'class' => 'button'
        ));

        echo Form::close();
      ?>
    </div>
  </div>
</div>

@stop