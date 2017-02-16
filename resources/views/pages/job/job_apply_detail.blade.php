@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper">
  <div class="top-header">
    <div class="detail-title">
      <h4 class="sub-title">รายละเอียดการสมัครงาน</h4>
      <h2 class="title">{{$jobName}}</h2>
      <div class="tag-group">
      </div>
    </div>
  </div>
</div>

<div class="detail container">

  <div class="detail-title">
    <h4 class="sub-title">รายละเอียด</h4>
    <div class="tag-group">
    </div>
  </div>

  @if(!empty($jobApply['message']))
    <div>{!!$jobApply['message']!!}</div>
    <div class="line space-top-bottom-30"></div>
  @endif

  <div class="row">
    <dt class="col-sm-3">สาขาที่ผู้สมัครสามารถทำงานได้</dt>
    <dd class="col-sm-9">
    @if(!empty($branches))
      @foreach($branches as $branch)

      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="title-with-icon space tick-green">{{$branch}}</div>
      </div>

      @endforeach
    @else
      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="">-</div>
      </div>
    @endif
    </dd>
  </div>
  <div class="line space-top-bottom-30"></div>

  <h3>ข้อมูลผู้สมัครงาน</h3>
  <div>โปรไฟล์</div>

  <div class="space-top-bottom-20">

    <div class="clearfix">
      <div class="profile-image pull-left">
        @if(!empty($profileImageUrl))
        <img src="{{$profileImageUrl}}">
        @endif
      </div>

      <div class="profile-info pull-left">
        <h3>{{$profile['name']}}</h3>
        <dl>
          <dt>เพศ</dt>
          <dd>{{$profile['gender']}}</dd>
        </dl>

        <dl>
          <dt>วันเกิด</dt>
          <dd>{{$profile['birthDate']}}</dd>
        </dl>
      </div>

    </div>

    <div class="space-top-bottom-20">
      
      @if(!empty($profile['Address']['_long_address']))
      <dl>
        <dt>ที่อยู่ปัจจุบัน</dt>
        <dd>{{$profile['Address']['_long_address']}}</dd>
      </dl>
      @endif

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

      @if(!empty($profile['Contact']['line']))
      <dl>
        <dt>Line ID</dt>
        <dd>{{$profile['Contact']['line']}}</dd>
      </dl>
      @endif

    </div>

    @if(!empty($careerObjective))
    <div class="space-top-50"></div>
    <h4>จุดมุ่งหมายในอาชีพ</h4>
    <div class="line"></div>
    <p>{!!$careerObjective!!}</p>
    @endif

    @if(!empty($PersonWorkingExperience))
    <div class="space-top-50"></div>
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
    @endif

    @if(!empty($PersonInternship))
    <div class="space-top-50"></div>
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
    @endif

    @if(!empty($PersonEducation))
    <div class="space-top-50"></div>
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
    @endif

    @if(!empty($PersonProject))
    <div class="space-top-50"></div>
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
    @endif

    @if(!empty($PersonCertificate))
    <div class="space-top-50"></div>
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
    @endif

    @if(!empty($skills))
    <div class="space-top-50"></div>
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
    @endif

    @if(!empty($languageSkills))
    <div class="space-top-50"></div>
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
    @endif

  </div>

</div>

@stop