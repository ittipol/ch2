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

  @include('components.form_error') 

  @if($personApplyJob['job_applying_status_id'] == 1)

    <div class="secondary-message-box space-bottom-30">
      <div class="secondary-message-box-inner">
        <div class="text-center">
          <img src="/images/common/resume.png">
          <h3>ใบสมัครงานถูกส่งแล้ว</h3>
          <p>โปรดรอการตอบรับจากผู้รับสมัคร</p>
        </div>
      </div>
    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 2)

    <div class="secondary-message-box space-bottom-30">
      <div class="secondary-message-box-inner">
        <div class="text-center">
          <img src="/images/common/resume.png">
          <h3>การสมัครงานนี้ถูกรับทราบแล้ว</h3>
          <p>บริษัท สถานประกอบการหรือผู้ที่เกี่ยวข้องได้รับทราบการสมัครของคุณแล้ว</p>
        </div>
      </div>
    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 3)

    <div class="secondary-message-box success">
      <div class="secondary-message-box-inner">
        <div>
          <h3>คุณผ่านการประเมินและถูกรับเข้าทำงานแล้ว</h3>
          <h5>โปรดยืนยันหากคุณต้องการทำงานในตำแหน่งนี้</h5>
        </div>
      </div>
    </div>
    <div class="secondary-message-box clean space-bottom-30">
      <div class="secondary-message-box-inner">
        <h4>ข้อเสนอและข้อตกลงต่างๆ ของตำแหน่งงานนี้</h4>
        <div class="line space-bottom-10"></div>
        <div>
          {!!$jobPositionDescription!!}
        </div>
      </div>

      <div class="message-box-button-group two-button clearfix">
        <div class="flat-button">
          <a class="button" data-right-side-panel="1" data-right-side-panel-target="#job_position_accept_panel">
            ตกลงเข้าทำงาน
          </a>
        </div>
        <div class="flat-button">
          <a class="button danger" data-right-side-panel="1" data-right-side-panel-target="#job_position_decline_panel">
            ปฏิเสธเข้าทำงาน
          </a>
        </div>
      </div>
    </div>

    <div id="job_position_accept_panel" class="right-size-panel form">
      <div class="right-size-panel-inner">
        @include('pages.job.components.job_position_accept')
        <div class="right-size-panel-close-button"></div>
      </div>
    </div>

    <div id="job_position_decline_panel" class="right-size-panel form">
      <div class="right-size-panel-inner">
        @include('pages.job.components.job_position_decline')
        <div class="right-size-panel-close-button"></div>
      </div>
    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 4)

    <div class="secondary-message-box info space-bottom-30">
      <div class="secondary-message-box-inner">
        <div>
          <h3>ไม่ผ่านการประเมิน</h3>
          <p>ดูเหมือนว่าผลลัพธ์การสมัครอาจจยังไม่เป็นตามที่คุณต้องกการ คุณต้องกการสมัครงานนี้อีกครั้งหรือไม่?</p>
        </div>
      </div>
      <div class="message-box-button-group clearfix">
        <div class="flat-button">
          <a href="{{$jobApplyUrl}}" class="button">
            สมัครงานนี้
          </a>
        </div>
      </div>
    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 5)

    <div class="secondary-message-box info space-bottom-30">
      <div class="secondary-message-box-inner">
        <div>
          <h3>การสมัครถูกยกเลิกแล้ว</h3>
          <p>ดูเหมือนว่าผลลัพธ์การสมัครอาจจยังไม่เป็นตามที่คุณต้องกการ คุณต้องกการสมัครงานนี้อีกครั้งหรือไม่?</p>
        </div>
      </div>
      <div class="message-box-button-group clearfix">
        <div class="flat-button">
          <a href="{{$jobApplyUrl}}" class="button">
            สมัครงานนี้
          </a>
        </div>
      </div>
    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 6)

    <div class="secondary-message-box info">
      <div class="secondary-message-box-inner">
        <div>
          <h3>คุณตกลงเข้าทำงานนี้</h3>
        </div>
      </div>
    </div>
    <div class="secondary-message-box clean space-bottom-30">
      <div class="secondary-message-box-inner">
        <h4>ข้อเสนอและข้อตกลงต่างๆ ของตำแหน่งงานนี้</h4>
        <div class="line space-bottom-10"></div>
        <div>
          {!!$jobPositionDescription!!}
        </div>
      </div>
    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 7)

    <div class="secondary-message-box info space-bottom-30">
      <div class="secondary-message-box-inner">
        <div>
          <h3>คุณปฏิเสธเข้าทำงานนี้</h3>
        </div>
      </div>
    </div>

  @endif

  <div class="text-right space-bottom-20">
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#job_appying_new_message_panel">ส่งข้อความไปยังผู้รับสมัคร</a>
  </div>

  <div id="job_appying_new_message_panel" class="right-size-panel form">
    <div class="right-size-panel-inner">
      @include('pages.job.components.job_applying_new_message')
      <div class="right-size-panel-close-button"></div>
    </div>
  </div>

  <div id="job_appying_reply_message_panel" class="right-size-panel form">
    <div class="right-size-panel-inner">
      @include('pages.job.components.job_applying_reply_message')
      <div class="right-size-panel-close-button"></div>
    </div>
  </div>

  <div class="tabs clearfix">
    <label>
      <input class="tab" type="radio" name="tabs"  data-tab="job_applying_detail">
      <span>รายละเอียดการสมัคร</span>
    </label>
    <label>
      <input class="tab" type="radio" name="tabs" data-tab="message_tab">
      <span>ข้อความ</span>
    </label>
  </div>

  <div id="job_applying_detail" class="tab-content">

    <div class="row">

      <div class="col-md-4 col-sm-12">

        <div class="detail-group">

          <div class="detail-group-info-section no-padding">

            <div class="detail-group-info">
              <h5 class="title">ชื้อบริษัทหรือสถานประกอบการ</h5>
              <a href="{{$shopUrl}}">
                <p>{{$shopName}}</p>
              </a>
            </div>

            <div class="detail-group-info">
              <h5 class="title">ตำแหน่งงาน</h5>
              <a href="{{$jobUrl}}">
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

  </div>

  <div id="message_tab" class="tab-content">

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
              <a data-right-side-panel="1" data-right-side-panel-target="#job_appying_reply_message_panel" data-reply-message="{{$message['id']}}">ตอบกลับ</a>
            </div>

          </div>

          @if(!empty($message['replyMessages']))
          <div class="reply-message-content">

            @foreach($message['replyMessages'] as $replyMessages)

              <div class="reply-message">

                <div class="sender-profile clearfix">
                  <div class="sender-profile-image">
                    <div class="profile-image" style="background-image:url({{$replyMessages['sender']['profileImage']}});"></div>
                  </div>
                  <div class="sender-profile-info">
                    <div><strong>{{$replyMessages['sender']['name']}}</strong></div>
                    <div>{{$replyMessages['createdDate']}}</div>
                  </div>
                </div>

                <div class="message">
                  <div class="message-box">
                    {!!$replyMessages['message']!!}
                  </div>
                
                  @if(!empty($replyMessages['attachedFiles']))
                  <div class="message-file-attachment clearfix">

                    @foreach($replyMessages['attachedFiles'] as $attachedFile)

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

                </div>

              </div>

            @endforeach
          </div>
          @endif

        @endforeach

      </div>

    @else

      <div class="list-empty-message text-center space-top-20">
        <img class="space-bottom-20 not-found-image" src="/images/common/not-found.png">
        <div>
          <h3>ยังไม่มีข้อความ</h3>
          <a class="button" data-right-side-panel="1" data-right-side-panel-target="#job_appying_new_message_panel">ส่งข้อความไปยังผู้รับสมัคร</a>
        </div>
      </div>

    @endif

  </div>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const tabs = new Tabs('job_applying_detail');
    tabs.load();

  });

</script>

@stop