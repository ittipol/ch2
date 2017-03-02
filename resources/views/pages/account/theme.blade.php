@extends('layouts.blackbox.main')
@section('content')

<header class="header-wrapper">
  <div class="container">
    <div class="header-top">
      <div class="header-title">ธีม</div>
    </div>
  </div>
  <div class="header-fix">
    <div class="header-title">ธีม</div>
  </div>

  <label class="hamburger-button" for="global_nav_trigger">
    ☰
    <input type="checkbox" id="global_nav_trigger" class="nav-trigger">
  </label>

</header>

<div class="container">

  <div class="row space-top-20">

    @foreach($themes as $color => $theme) 

    <div class="pull-left">
      <label class="tile-color {{$color}}">
        <?php
          echo Form::radio('theme', $theme);
        ?>
      </label>
    </div>

    @endforeach

  </div>

</div>

<script type="text/javascript">

  $(document).ready(function(){
    
  });

</script>

@stop