@extends('layouts.blackbox.main')
@section('content')
  <div class="container error-page">
    <div class="row">
      <div class="col-lg-12">
        <div class="form-error-messages">
          <div class="form-error-messages-inner">
            <h3>{{$error['message']}}</h3>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="line space-top-bottom-20"></div>
      </div>
      <div class="col-lg-12">
        <a class="button pull-right" href="{{URL::to('/')}}">หน้าแรก</a>
      </div>
    </div>
  </div>
@stop