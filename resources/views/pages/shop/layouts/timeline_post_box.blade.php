<div class="box timeline">
  <div class="box-header">
    <h4 class="box-header-title">
      <div class="primary-title">
        <span class="post-owner-name">
          <img class="icon-before-title" src="/images/icons/message-blue.png">
          {{$timeline['owner']}}
        </span>
        {{$timeline['title']}}
      </div>
      <div class="secondary-title">{{$timeline['createdDate']}}</div>
    </h4>
  </div>

  <div class="box-content padding-15">
    <div class="timeline-message">{!!$timeline['message']!!}</div>

    @if(!empty($timeline['relatedData']))
    <a href="{{$timeline['relatedData']['detailUrl']}}" class="timeline-content clearfix">
      <div class="image-tile pull-left">
        <div class="timeline-content-image" style="background-image:url({{$timeline['relatedData']['image']}});"></div>
      </div>
      <div class="timeline-content-info pull-left">
        <div class="title">{{$timeline['relatedData']['title']}}</div>
        <div class="description">{{$timeline['relatedData']['description']}}</div>
      </div>
    </a>
    @endif

  </div>

  @if(!empty($timeline['cancelPinUrl']) || !empty($timeline['deleteUrl']))
  <div class="additional-option">
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="additional-option-content">
      @if(!empty($timeline['cancelPinUrl']))
      <a href="{{$timeline['cancelPinUrl']}}">ยกเลิกการตรึงข้อความ</a>
      @endif
      @if(!empty($timeline['deleteUrl']))
      <a href="{{$timeline['deleteUrl']}}"  data-modal="1" data-modal-title="ต้องการลบข้อความใช่หรือไม่">ลบข้อความ</a>
      @endif
    </div>
  </div>
  @endif

</div>