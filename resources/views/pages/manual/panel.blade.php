@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">
        <div class="btn-group pull-right">
          <a href="{{URL::to('manual')}}" class="btn btn-secondary">กลับไปหน้าวิธีการใช้งาน</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container space-top-30">

  <h3 class="space-bottom-50">{{$title}}</h3>

  <div class="row">

    <div class="col-xs-12">

      <div class="overflow-hidden">

        @include($page)

      </div>

    </div>

  </div>

</div>

@stop