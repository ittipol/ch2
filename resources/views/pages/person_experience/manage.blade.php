@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">เรซูเม่</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="tile-nav-group space-top-20 clearfix">

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{URL::to('resume/profile/edit')}}">
          <img src="/images/common/pencil.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{URL::to('resume/profile/edit')}}">
          <h4 class="tile-nav-title">แก้ไขโปรไฟล์</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{URL::to('resume/edit')}}">
          <img src="/images/common/resume.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{URL::to('resume/edit')}}">
          <h4 class="tile-nav-title">เพิ่มประวัติการทำงานและทักษะ</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{URL::to('person/private_website/list')}}">
          <img src="/images/common/globe.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{URL::to('person/private_website/list')}}">
          <h4 class="tile-nav-title">เว็บไซต์ส่วนตัว</h4>
        </a>
      </div>
    </div>

  </div>

  <div class="line"></div>

  <div class="content-box">
    <div class="content-box-inner">
      <div class="row">

        <div class="col-md-6 col-xs-12">
          <div class="content-box-panel overlay-bg">
            <h5>โปรไฟล์ของคุณ</h5>

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

              <div class="main-sub-content">
                <div><strong>วันเกิด</strong>: {{$profile['birthDate']}}</div>
              </div>

            </div>

          </div>
        </div>

        <div class="col-md-6 col-xs-12">

          <div class="profile-contact-info">

            <h4>ติดต่อ</h4>
            <div class="line space-top-bottom-10"></div>

            <dl>
              <dt>หมายเลขโทรศัพท์</dt>
              @if(!empty($profile['Contact']['phone_number']))
              <dd>{{$profile['Contact']['phone_number']}}</dd>
              @else
              <dd>-</dd>
              @endif
            </dl>

            <dl>
              <dt>อีเมล</dt>
              @if(!empty($profile['Contact']['email']))
              <dd>{{$profile['Contact']['email']}}</dd>
              @else
              <dd>-</dd>
              @endif
            </dl>

          </div>

        </div>

      </div>
    </div>
  </div>

  <h3 class="space-bottom-10">ประวัติการทำงานและทักษะ</h3>
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

    @if(!empty($privateWebsites))
    <div class="person-experience-info">
      <h4>เว็บไซต์ส่วนตัว</h4>
      <div class="line"></div>
      <div class="list-group">
        @foreach($privateWebsites as $privateWebsite)
          <div class="list-row row">
            <div class="col-xs-9">
              <h4><a href="{{$privateWebsite['url']}}">{{$privateWebsite['website_url']}}</a></h4>
              <h5>{{$privateWebsite['websiteType']}}</h5>
            </div>
          </div>
        @endforeach
      </div>
    </div>
    @endif

  </div>

  <a href="{{$experienceDetailUrl}}" class="button">ไปยังหน้าแสดงเรซูเม่</a>
  
</div>

@stop