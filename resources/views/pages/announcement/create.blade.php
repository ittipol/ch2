@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">
          เพิ่มประกาศ
        </div>
        <div>เพิ่มประกาศของคุณ</div>
      </div>
    </div>
  </div>

  <div class="line space-top-bottom-30"></div>

    <div class="row">

      <div class="col-lg-4 col-md-4 col-sm-6 col-sm-12">
        <div class="choice-box">
          <div class="inner">
            <h4>ประกาศขายสินค้า</h4>
            <p>ประกาศขายสินค้า</p>
            <a href="{{URL::to('manage/product/add')}}" class="button">เพิ่มประกาศขายสินค้า</a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-4 col-sm-6 col-sm-12">
        <div class="choice-box">
          <div class="inner">
            <h4>ประกาศรับสมัครพนักงาน</h4>
            <p>ประกาศรับสมัครพนักงาน</p>
            <a href="{{URL::to('job/add')}}" class="button">เพิ่มประกาศรับสมัครพนักงาน</a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-4 col-sm-6 col-sm-12">
        <div class="choice-box">
          <div class="inner">
            <h4>โฆษณาเช่า-ขายอสังหาริมทรัพย์</h4>
            <p>โฆษณาเช่า-ขายอสังหาริมทรัพย์</p>
            <a href="{{URL::to('real-estate/add')}}" class="button">เพิ่มโฆษณาอสังหาริมทรัพย์</a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-4 col-sm-6 col-sm-12">
        <div class="choice-box">
          <div class="inner">
            <h4>โฆษณาร้านค้าและการบริการของคุณ</h4>
            <p>โฆษณาร้านค้าและการบริการของคุณ</p>
            <a href="{{URL::to('real-estate/add')}}" class="button">เพิ่มโฆษณาโฆษณาร้านค้าและการบริการ</a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-4 col-sm-6 col-sm-12">
        <div class="choice-box">
          <div class="inner">
            <h4>เพิ่มร้านค้าของคุณลงในชุมชน</h4>
            <p>คำอธิบาย</p>
            <a href="{{URL::to('real-estate/add')}}" class="button">รายละเอียดเพิ่มเติม</a>
          </div>
        </div>
      </div>

    </div> 


</div>
@stop