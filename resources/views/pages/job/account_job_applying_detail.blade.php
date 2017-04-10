@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h4 class="sub-title">งานที่สมัคร</h4>
        <h2 class="title">{{$jobName}}</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="row">

    <div class="col-md-4 col-sm-12">

      <div class="detail-group">
        <h4>รายละเอียดการสั่งซื้อ</h4>
        <div class="line"></div>

        <div class="detail-group-info-section">

          <div class="detail-group-info">
            <h5 class="title">ชื้อบริษัทหรือร้านค้าที่ขายสินค้า</h5>
            <a href="{{$shopUrl}}">
              <p>{{$shopName}}</p>
            </a>
          </div>

          <div class="detail-group-info">
            <h5 class="title">ชื้อผู้ซื้อ</h5>
            <a href="$jobUrl">
              <p>{{$jobName}}</p>
            </a>
          </div>

          <div class="detail-group-info">
            <h5 class="title">วันที่สมัคร</h5>
            <p>{{$createdDate}}</p>
          </div>

        </div>
      </div>

    </div>

  </div>

  <h4>ข้อความ</h4>
  <div class="line space-bottom-20"></div>

  @if(!empty($messages))
    <div class="message-content">
      @foreach($messages as $message)
        
        <div class="sender-profile clearfix">
          <div class="sender-profile-image">
            <div class="profile-image" style="background-image:url({{$message['sender']['profileImage']}});"></div>
          </div>
          <div class="sender-profile-info">
            <div><strong>{{$message['sender']['name']}}</strong></div>
            <div>{{$message['createdDate']}}</div>
          </div>
        </div>

        <div class="message">
          <div class="message-box">
            {!!$message['message']!!}
          </div>

          @if(!empty($message['attachedFiles']))
          <div class="message-file-attachment clearfix">

            @foreach($message['attachedFiles'] as $attachedFile)

              <a href="{{$attachedFile['downloadUrl']}}" class="attached-file-box clearfix">
                <div class="attached-file-image">
                  <img src="/images/icons/document-white.png">
                </div>
                <div class="attached-file-info">
                  <div class="attached-file-title">
                    <h4>{{$attachedFile['filename']}}</h4>
                    <h5>{{$attachedFile['filesize']}}</h5>
                  </div>
                </div>
              </a>

            @endforeach
          </div>
          @endif
          <div class="message-reply-flat-button text-right">
            <a href="">ตอบกลับ</a>
          </div>

        </div>

      @endforeach
    </div>

  @else

    <div class="list-empty-message text-center space-top-20">
      <img class="space-bottom-20 not-found-image" src="/images/common/not-found.png">
      <div>
        <h3>ยังไม่มีการตอบกลับจากผู้ประกาศงาน</h3>
      </div>
    </div>

  @endif

</div>

@stop