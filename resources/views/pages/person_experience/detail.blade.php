@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper">
  <div class="top-header">
    <div class="detail-title">
      <h4 class="sub-title">ประวัติการทำงาน</h4>
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

  <h3>ประวัติการทำงาน</h3>
  <div>โปรไฟล์</div>

  <div class="space-top-bottom-20">

    <div class="row">

      <div class="col-lg-5 col-sm-12">

        <div class="clearfix">
          <div class="account pull-left">
            @if(!empty($profileImageUrl))
            <div class="profile-image" style="background-image:url({{$profileImageUrl}});"></div>
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

      </div>

      <div class="col-lg-7 col-sm-12">

        <div class="space-top-bottom-10">
          
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

</div>

@stop