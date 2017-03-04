@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">ประวัติการทำงาน</h2>
        <div class="tag-group">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="detail container">

  <div class="content-box">
    <div class="content-box-inner">
      <div class="row">

        <div class="col-md-6 col-sm-12">
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

              <div class="main-sub-content">
                <div><strong>วันเกิด</strong>: {{$profile['birthDate']}}</div>
              </div>

            </div>

          </div>
        </div>

        <div class="col-md-6 col-sm-12">

          <div class="profile-contact-info">

            <h4>ติดต่อ</h4>
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

            @if(!empty($profile['Contact']['line']))
            <dl>
              <dt>Line ID</dt>
              <dd>{{$profile['Contact']['line']}}</dd>
            </dl>
            @endif

          </div>

        </div>

      </div>
    </div>
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

@stop