@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}job_applying" class="btn btn-secondary">กลับไปหน้ารายชื่อผู้สมัครงาน</a>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h4 class="sub-title">รายละเอียดการสมัครงาน</h4>
        <h2 class="title">{{$jobName}}</h2>
      </div>
    </div>
  </div>
</div>

<div class="detail container">

  @include('components.form_error')

  @if($personApplyJob['job_applying_status_id'] == 1)

    <div class="secondary-message-box space-bottom-30">
      <div class="secondary-message-box-inner">
        <div class="text-center">
          <img src="/images/common/resume.png">
          <h3>ใบสมัครงาน</h3>
          <p>ตอบรับการสมัครจะเป็นการบอกผู้สมัครให้ทราบว่าบริษัทหรือผู้ที่เกี่ยวข้องได้รับทราบการสมัครแล้ว หรือหากผู้สมัครมีคุณสมบัติไม่ตรงตามที่ต้องการสามารถยกเลิกการสมัครได้</p>
        </div>
      </div>
      <div class="message-box-button-group two-button clearfix">
        <div class="flat-button">
          <a class="button" data-right-side-panel="1" data-right-side-panel-target="#job_appying_accept_panel">
            ตอบรับการสมัคร
          </a>
        </div>
        <div class="flat-button">
          <a class="button danger" data-right-side-panel="1" data-right-side-panel-target="#job_appying_cancel_panel">
            ยกเลิกการสมัคร
          </a>
        </div>
      </div>
    </div>

    <div id="job_appying_accept_panel" class="right-size-panel form">
      <div class="right-size-panel-inner">
        @include('pages.job.components.job_applying_accept')
        <div class="right-size-panel-close-button"></div>
      </div>
    </div>

    <div id="job_appying_cancel_panel" class="right-size-panel form">
      <div class="right-size-panel-inner">
        @include('pages.job.components.job_applying_cancel')
        <div class="right-size-panel-close-button"></div>
      </div>
    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 2)

    <div class="secondary-message-box space-bottom-30">
      <div class="secondary-message-box-inner">
        <div class="text-center">
          <img src="/images/common/resume.png">
          <h3>ตอบรับการสมัครนี้แล้ว</h3>
          <p>เมื่อได้ผลลัพธ์การสมัครแล้ว สามารถแจ้งผลลัพธ์ไปยังผู้สมัครโดยตรงด้วยการเลือกตัวเลือกข้างล่างนี้</p>
        </div>
      </div>
      <div class="message-box-button-group three-button clearfix">
        <div class="flat-button">
          <a class="button" data-right-side-panel="1" data-right-side-panel-target="#job_appying_passed_panel">
            ผ่านการประเมินและรับเข้าทำงาน
          </a>
        </div>
        <div class="flat-button">
          <a class="button danger" data-right-side-panel="1" data-right-side-panel-target="#job_appying_not_pass_panel">
            ไม่ผ่านการประเมิน
          </a>
        </div>
        <div class="flat-button">
          <a class="button warning" data-right-side-panel="1" data-right-side-panel-target="#job_appying_cancel_panel">
            ยกเลิกการสมัคร
          </a>
        </div>
      </div>
    </div>

    <div id="job_appying_passed_panel" class="right-size-panel form">
      <div class="right-size-panel-inner">
        @include('pages.job.components.job_applying_passed')
        <div class="right-size-panel-close-button"></div>
      </div>
    </div>

    <div id="job_appying_not_pass_panel" class="right-size-panel form">
      <div class="right-size-panel-inner">
        @include('pages.job.components.job_applying_not_pass')
        <div class="right-size-panel-close-button"></div>
      </div>
    </div>

    <div id="job_appying_cancel_panel" class="right-size-panel form">
      <div class="right-size-panel-inner">
        @include('pages.job.components.job_applying_cancel')
        <div class="right-size-panel-close-button"></div>
      </div>
    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 3)

    <div class="secondary-message-box info space-bottom-30">
      <div class="secondary-message-box-inner">
        <div>
          <h3>ผู้สมัครผ่านการประเมินแล้ว</h3>
          <h5>โปรดรอการยืนยันการเข้าทำงานจากผู้สมัคร</h5>
        </div>
      </div>
      <div class="message-box-button-group clearfix">
        <div class="flat-button">
          <a class="button danger" data-right-side-panel="1" data-right-side-panel-target="#job_appying_cancel_panel">
            ยกเลิกการสมัคร
          </a>
        </div>
      </div>
    </div>

    <div id="job_appying_cancel_panel" class="right-size-panel form">
      <div class="right-size-panel-inner">
        @include('pages.job.components.job_applying_cancel')
        <div class="right-size-panel-close-button"></div>
      </div>
    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 4)

    <div class="secondary-message-box info space-bottom-30">
      <div class="secondary-message-box-inner">
        <div>
          <h3>ผู้สมัครไม่ผ่านการประเมิน</h3>
        </div>
      </div>
    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 5)

    <div class="secondary-message-box info space-bottom-30">
      <div class="secondary-message-box-inner">
        <div>
          <h3>การสมัครถูกยกเลิกแล้ว</h3>
        </div>
      </div>
    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 6)

    <div class="secondary-message-box info">
      <div class="secondary-message-box-inner">
        <div>
          <h3>ผู้สมัครตกลงเข้าทำงานนี้</h3>
        </div>
      </div>

      <div class="secondary-message-box-inner clean">
        <h4>คำกล่าวถึงบรืษัทหรือผู้ที่เกี่ยวข้อง</h4>
        <div class="line space-bottom-10"></div>
        <div>
          {!!$jobApplyHistory['message']!!}
        </div>
      </div>

    </div>

  @elseif($personApplyJob['job_applying_status_id'] == 7)

    <div class="secondary-message-box info">
      <div class="secondary-message-box-inner">
        <div>
          <h3>ผู้สมัครปฏิเสธเข้าทำงานนี้</h3>
        </div>
      </div>
    </div>
    @if(!empty($jobApplyHistory['message']))
      <div class="secondary-message-box clean space-bottom-30">
        <div class="secondary-message-box-inner">
          <h4>คำอธิบายว่าทำไมถึงปฏิเสธเข้าทำงาน</h4>
          <div class="line space-bottom-10"></div>
          <div>
            {!!$jobApplyHistory['message']!!}
          </div>
        </div>
      </div>
    @endif

  @endif

  <div class="text-right space-bottom-20">
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#job_appying_new_message_panel">ส่งข้อความไปยังผู้สมัคร</a>
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

  @if(!empty($messageFromApplicant))
    <div class="detail-title">
      <h3>ข้อความจากผู้สมัคร</h3>
      <div class="line space-bottom-10"></div>
      <div>
        {!!$messageFromApplicant!!}
      </div>
    </div>
  @endif

  <div class="detail-title">
    <h3>รายละเอียดจากผู้สมัคร</h3>
    <div class="line space-top-10"></div>
  </div>

  <div class="space-top-20">
    <h4>ตำแหน่งที่สมัคร</h4>
    <div>{{$jobName}}</div>
  </div>
  
  @if($hasBranch)
  <div class="space-top-20">
    <div class="row">
      <dt class="col-sm-3">สาขาที่ผู้สมัครสามารถเข้าทำงานได้</dt>
      <dd class="col-sm-9">
      @if(!empty($branches))
        @foreach($branches as $branch)

        <div class="col-lg-4 col-md-4 col-sm-6">
          <div class="title-with-icon space tick-green">{{$branch}}</div>
        </div>

        @endforeach
      @else
        <div class="col-lg-4 col-md-4 col-sm-6">
          <div>ไม่ได้ระบุ</div>
        </div>
      @endif
      </dd>
    </div>
  </div>
  @endif

  @if(!empty($attachedFiles))
  <div class="space-top-20">

    <h4>ไฟล์จากผู้สมัคร</h4>
    <div class="message-file-attachment clearfix">

      @foreach($attachedFiles as $attachedFile)

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
  </div>
  @endif

  <div class="line space-top-20 space-bottom-30"></div>

  <div class="tabs clearfix">
    <label>
      <input class="tab" type="radio" name="tabs"  data-tab="person_experience_tab">
      <span>ประวัติผู้สมัคร</span>
    </label>
    <label>
      <input class="tab" type="radio" name="tabs" data-tab="message_tab">
      <span>ข้อความ</span>
    </label>
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
          <a class="button" data-right-side-panel="1" data-right-side-panel-target="#job_appying_new_message_panel">ส่งข้อความไปยังผู้สมัคร</a>
        </div>
      </div>

    @endif

  </div>

  <div id="person_experience_tab" class="tab-content">

    <h3>ข้อมูลผู้สมัครงาน</h3>

    <div class="space-top-bottom-10">

      <div class="content-box">
        <div class="content-box-inner">
          <div class="row">

            <div class="col-md-6 col-xs-12">
              <div class="content-box-panel overlay-bg">
                <h5>โปรไฟล์</h5>

                <div class="row">

                  <div class="col-sm-12">
                    <div class="image-frame elem-center">
                      @if(!empty($profileImageUrl))
                      <div class="content-box-main-image" style="background-image:url({{$profileImageUrl}});"></div>
                      @endif
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <div class="profile-info text-center space-top-20">
                      <h3>{{$profile['name']}}</h3>
                    </div>
                  </div>

                </div>

                <div class="line space-top-bottom-20"></div>

                <div class="content-box-main-sub-content clearfix">
                  
                  <div class="main-sub-content">
                    <div><strong>เพศ</strong>: {{$profile['gender']}}</div>
                  </div>

                </div>

              </div>
            </div>

            <div class="col-md-6 col-xs-12">

              <div class="profile-contact-info">

                <h4>ติดต่อผู้สมัคร</h4>
                <div class="line space-top-bottom-10"></div>

                @if(!empty($profile['Contact']['phone_number']))
                <dl>
                  <dt>หมายเลขโทรศัพท์</dt>
                  <dd>{{$profile['Contact']['phone_number']}}</dd>
                </dl>
                @endif

                @if(!empty($profile['Contact']['email']))
                <dl>
                  <dt>อีเมล</dt>
                  <dd>{{$profile['Contact']['email']}}</dd>
                </dl>
                @endif

              </div>

            </div>

          </div>
        </div>
      </div>

      @if($hasResume)

      @if(!empty($privateWebsites))
      <div class="person-private-website-content">
        <h4>เว็บไซต์ส่วนตัวของผู้สมัคร</h4>
        <div class="line"></div>
        <div class="row">
        @foreach($privateWebsites as $privateWebsite)
          <div class="col-md-6 col-xs-12">
            <div class="space-top-20">
              <h4><a href="{{$privateWebsite['url']}}">{{$privateWebsite['website_url']}}</a></h4>
              <h5>{{$privateWebsite['websiteType']}}</h5>
              <a href="{{$privateWebsite['url']}}" class="button">เข้าสู่หน้าเว็บ</a>
            </div>
          </div>
        @endforeach
        </div>
      </div>
      @endif

      <h3 class="space-bottom-10">เรซูเม่</h3>

      <div class="person-experience-content">

        @if(!empty($careerObjective))
          <div class="person-experience-info">
            <h4>จุดมุ่งหมายในอาชีพ</h4>
            <div class="line"></div>
            <p>{!!$careerObjective!!}</p>
          </div>
        @endif

        @if(!empty($PersonWorkingExperience))
        <div class="person-experience-info">
          <h4>ประสบการณ์การทำงาน</h4>
          <div class="line"></div>
          <div class="list-group">
            @foreach($PersonWorkingExperience as $detail)
              <div class="list-row row">
                <div class="col-xs-9">
                  <h4>{{$detail['message']}}</h4>
                  <h5>{{$detail['peroid']}}</h5>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        @if(!empty($PersonInternship))
        <div class="person-experience-info">
          <h4>ประสบการณ์การฝึกงาน</h4>
          <div class="line"></div>
          <div class="list-group">
            @foreach($PersonInternship as $detail)
              <div class="list-row row">
                <div class="col-xs-9">
                  <h4>{{$detail['company']}}</h4>
                  <h5>{{$detail['peroid']}}</h5>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        @if(!empty($PersonEducation))
        <div class="person-experience-info">
          <h4>ประวัติการศึกษา</h4>
          <div class="line"></div>
          <div class="list-group">
            @foreach($PersonEducation as $detail)
              <div class="list-row row">
                <div class="col-xs-9">
                  <h4>{{$detail['message']}}</h4>
                  <h5>{{$detail['peroid']}}</h5>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        @if(!empty($PersonProject))
        <div class="person-experience-info">
          <h4>โปรเจค</h4>
          <div class="line"></div>
          <div class="list-group">
            @foreach($PersonProject as $detail)
              <div class="list-row row">
                <div class="col-xs-9">
                  <h4>{{$detail['name']}}</h4>
                  <h5>{{$detail['peroid']}}</h5>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        @if(!empty($PersonCertificate))
        <div class="person-experience-info">
          <h4>ประกาศนียบัตรและการฝึกอบรม</h4>
          <div class="line"></div>
          <div class="list-group">
            @foreach($PersonCertificate as $detail)
              <div class="list-row row">
                <div class="col-xs-9">
                  <h4>{{$detail['name']}}</h4>
                  <h5>{{$detail['peroid']}}</h5>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        @if(!empty($skills))
        <div class="person-experience-info">
          <h4>ทักษะและความสามารถ</h4>
          <div class="line"></div>
          <div class="list-group">
            @foreach($skills as $skill)
              <div class="list-row row">
                <div class="col-xs-9">
                  <h4>{{$skill['skill']}}</h4>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        @if(!empty($languageSkills))
        <div class="person-experience-info">
          <h4>ภาษาที่สามารถสื่อสารได้</h4>
          <div class="line"></div>
          <div class="list-group">
            @foreach($languageSkills as $languageSkill)
              <div class="list-row row">
                <div class="col-xs-9">
                  <h4>{{$languageSkill['name']}}</h4>
                  <h5>{{$languageSkill['level']}}</h5>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

      </div>

      @endif
      
    </div>
    

  </div>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const tabs = new Tabs('person_experience_tab');
    tabs.load();

  });

</script>

@stop