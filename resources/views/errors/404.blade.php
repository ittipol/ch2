@extends('layouts.blackbox.main')
@section('content')
  <div class="container error-page">
    <div class="row">
      <div class="col-lg-12">
        <div class="form-error-messages">
          <div class="form-error-messages-inner">
            <h3>ขออภัย ไม่พบหน้านี้</h3>
            <p>The page you requested 
has moved or doesn't exist. (Error 404) What next? If you typed in the address, double-check your spelling. If you followed a link, it's probably broken, and we've been notified. Try finding what you need below:</p>
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