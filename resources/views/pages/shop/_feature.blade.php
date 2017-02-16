@extends('layouts.blackbox.main')
@section('content')

  <div class="container">

    <div class="container-header">
      <div class="row">
        <div class="col-lg-6 col-sm-12">
          <div class="title">
            คุณสมบัติที่สำคัญ
          </div>
        </div>
      </div>
    </div>

    <div class="line space-top-bottom-30"></div>
      
    <div class="row">
      <div class="col-lg-6">
        <h3>เชื่อมต่อธุรกิจของคุณกับผู้คนในขลบุรี</h3>
        <p>เพิ่มธุรกิจของคุณ และให้เราทำหน้าที่เชื่อมต่อธุรกิจของคุณกับผู้คนในขลบุรี</p>
      </div>
      <div class="col-lg-6">
        <h3>ร้านค้าออนไลน์</h3>
        <p>ขายสินค้าและจัดการสินค้าของคุณ รวมถึงสร้งโปรโมชั่นเพื่อเพิ่มยอดขายของคุณ</p>
      </div>
      <div class="col-lg-6">
        <h3>ลงประกาศงาน</h3>
        <p>ลงประกาศงานเพื่อหาพนักงานใหม่ๆ หรือ ค้นหาโดยตรงจากประวัติการทำของบุคคลนั้นๆ<br/>รวมถึงการจัดการและตรวจสอบรายชื่อผู้ที่สนใจงานของคุณ</p>
      </div>
      <div class="col-lg-6">
        <h3>โฆษณาธุรกิจและงานบริการ</h3>
        <p>โฆษณางานบริการของคุณ เพื่อให้ลูกทราบถึงธุรกิจงานบริการต่างๆ ของคุณ</p>
      </div>
    </div>

    <div class="line space-top-bottom-30"></div>

    <a href="{{URL::to('business/shop_create')}}" class="button">สร้างร้านค้า</a>

  </div>

@stop