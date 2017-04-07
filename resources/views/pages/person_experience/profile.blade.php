@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">เพิ่มประวัติการทำงานและทักษะ</h2>
        <div class="tag-group">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <?php 
    echo Form::model($_formData, [
      'id' => 'main_form',
      'method' => 'PATCH',
      'enctype' => 'multipart/form-data'
    ]);
  ?>

  <h4>ใครบ้างที่สามารถเห็นประวัติการทำงานของฉันได้</h4>
  <div class="input-group clearfix">
    <div class="input-item">
      <?php
        echo Form::select('access_level', $accessLevels);
      ?>
    </div>
    <div class="input-item">
      <?php
        echo Form::submit('บันทึก' , array(
          'class' => 'button'
        ));
      ?>
    </div>
  </div>

  <?php
    echo Form::close();
  ?>

  <div class="line grey space-top-bottom-20"></div>
  
  <h4>จุดมุ่งหมายในอาชีพ</h4>
  <div class="clearfix">
    <div class="tile-nav xs pull-left">
      <div class="tile-nav-image">
        <a href="{{URL::to('experience/career_objective')}}">
          <img src="/images/common/pencil.png">
        </a>
      </div>
    </div>
    @if(empty($careerObjective))
    <a href="{{URL::to('experience/career_objective')}}" class="tile-nav-title pull-left">เพิ่มจุดมุ่งหมายในอาชีพ</a>
    @else
    <a href="{{URL::to('experience/career_objective')}}" class="tile-nav-title pull-left">แก้ไข เพิ่มเติมจุดมุ่งหมายในอาชีพ</a>
    @endif
  </div>

  <div class="line grey space-top-bottom-20"></div>
  <h4>ประสบการณ์การทำงาน</h4>
  <div class="clearfix">
    <div class="tile-nav xs pull-left">
      <div class="tile-nav-image">
        <a href="{{URL::to('experience/working_add')}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
    </div>
    <h4 class="tile-nav-title pull-left">เพิ่ม</h4>
  </div>
  @if(!empty($PersonWorkingExperience))
  <div class="list-group">
    @foreach($PersonWorkingExperience as $detail)
      <div class="list-row row">
        <div class="col-xs-9">
          <h4>{{$detail['message']}}</h4>
          <h5>{{$detail['peroid']}}</h5>
        </div>
        <div class="col-xs-3">
          <div class="additional-option round pull-right">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$detail['editUrl']}}">แก้ไข</a>
              <a data-modal="1" href="{{$detail['deleteUrl']}}">ลบ</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @endif
 
  <div class="line grey space-top-bottom-20"></div>
  <h4>ประสบการณ์การฝึกงาน</h4>
  <div class="clearfix">
    <div class="tile-nav xs pull-left">
      <div class="tile-nav-image">
        <a href="{{URL::to('experience/internship_add')}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
    </div>
    <h4 class="tile-nav-title pull-left">เพิ่ม</h4>
  </div>
  @if(!empty($PersonInternship))
  <div class="list-group">
    @foreach($PersonInternship as $detail)
      <div class="list-row row">
        <div class="col-xs-9">
          <h4>{{$detail['company']}}</h4>
          <h5>{{$detail['peroid']}}</h5>
        </div>
        <div class="col-xs-3">
          <div class="additional-option round pull-right">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$detail['editUrl']}}">แก้ไข</a>
              <a data-modal="1" href="{{$detail['deleteUrl']}}">ลบ</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @endif

  <div class="line grey space-top-bottom-20"></div>
  <h4>ประวัติการศึกษา</h4>
  <div class="clearfix">
    <div class="tile-nav xs pull-left">
      <div class="tile-nav-image">
        <a href="{{URL::to('experience/education_add')}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
    </div>
    <h4 class="tile-nav-title pull-left">เพิ่ม</h4>
  </div>
  @if(!empty($PersonEducation))
  <div class="list-group">
    @foreach($PersonEducation as $detail)
      <div class="list-row row">
        <div class="col-xs-9">
          <h4>{{$detail['message']}}</h4>
          <h5>{{$detail['peroid']}}</h5>
        </div>
        <div class="col-xs-3">
          <div class="additional-option round pull-right">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$detail['editUrl']}}">แก้ไข</a>
              <a data-modal="1" href="{{$detail['deleteUrl']}}">ลบ</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @endif

  <div class="line grey space-top-bottom-20"></div>
  <h4>โปรเจค</h4>
  <div class="clearfix">
    <div class="tile-nav xs pull-left">
      <div class="tile-nav-image">
        <a href="{{URL::to('experience/project_add')}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
    </div>
    <h4 class="tile-nav-title pull-left">เพิ่ม</h4>
  </div>
  @if(!empty($PersonProject))
  <div class="list-group">
    @foreach($PersonProject as $detail)
      <div class="list-row row">
        <div class="col-xs-9">
          <h4>{{$detail['name']}}</h4>
          <h5>{{$detail['peroid']}}</h5>
        </div>
        <div class="col-xs-3">
          <div class="additional-option round pull-right">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$detail['editUrl']}}">แก้ไข</a>
              <a data-modal="1" href="{{$detail['deleteUrl']}}">ลบ</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @endif

  <div class="line grey space-top-bottom-20"></div>
  <h4>ประกาศนียบัตรและการฝึกอบรม</h4>
  <div class="clearfix">
    <div class="tile-nav xs pull-left">
      <div class="tile-nav-image">
        <a href="{{URL::to('experience/certificate_add')}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
    </div>
    <h4 class="tile-nav-title pull-left">เพิ่ม</h4>
  </div>
  @if(!empty($PersonCertificate))
  <div class="list-group">
    @foreach($PersonCertificate as $detail)
      <div class="list-row row">
        <div class="col-xs-9">
          <h4>{{$detail['name']}}</h4>
          <h5>{{$detail['peroid']}}</h5>
        </div>
        <div class="col-xs-3">
          <div class="additional-option round pull-right">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$detail['editUrl']}}">แก้ไข</a>
              <a data-modal="1" href="{{$detail['deleteUrl']}}">ลบ</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @endif

  <div class="line grey space-top-bottom-20"></div>
  <h4>ทักษะและความสามารถ</h4>
  <div class="clearfix">
    <div class="tile-nav xs pull-left">
      <div class="tile-nav-image">
        <a href="{{URL::to('experience/skill_add')}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
    </div>
    <h4 class="tile-nav-title pull-left">เพิ่ม</h4>
  </div>
  @if(!empty($skills))
  <div class="list-group">
    @foreach($skills as $skill)
      <div class="list-row row">
        <div class="col-xs-9">
          <h4>{{$skill['skill']}}</h4>
        </div>
        <div class="col-xs-3">
          <div class="additional-option round pull-right">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$skill['editUrl']}}">แก้ไข</a>
              <a data-modal="1" href="{{$skill['deleteUrl']}}">ลบ</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @endif

  <div class="line grey space-top-bottom-20"></div>
  <h4>ภาษาที่สามารถสื่อสารได้</h4>
  <div class="clearfix">
    <div class="tile-nav xs pull-left">
      <div class="tile-nav-image">
        <a href="{{URL::to('experience/language_skill_add')}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
    </div>
    <h4 class="tile-nav-title pull-left">เพิ่ม</h4>
  </div>
  @if(!empty($languageSkills))
  <div class="list-group">
    @foreach($languageSkills as $languageSkill)
      <div class="list-row row">
        <div class="col-xs-9">
          <h4>{{$languageSkill['name']}}</h4>
          <h5>{{$languageSkill['level']}}</h5>
        </div>
        <div class="col-xs-3">
          <div class="additional-option round pull-right">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$languageSkill['editUrl']}}">แก้ไข</a>
              <a data-modal="1" href="{{$languageSkill['deleteUrl']}}">ลบ</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @endif
  
</div>

@stop