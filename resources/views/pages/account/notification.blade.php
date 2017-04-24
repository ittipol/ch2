@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">การแจ้งเตือน</h2>
      </div>
    </div>
  </div>
</div>

<div class="container list">

  @if(!empty($_pagination['data']))

    <div class="list-h">
    @foreach($_pagination['data'] as $data)
      <div class="list-h-item clearfix">

        <a href="{{$data['url']}}" class="list-image pull-left">
          <img src="{{$data['image']}}">
        </a>

        <div class="col-md-11 col-xs-8">

          <div class="row">

            <div class="col-xs-12 list-content">
              <a href="{{$data['url']}}">
                <h4 class="primary-info">{!!$data['title']!!}</h4>
                <div class="secondary-info">{{$data['createdDate']}}</div>
              </a>
            </div>

          </div>

        </div>

      </div>
    @endforeach
    </div>

    @include('components.pagination')

  @else

  <div class="list-empty-message text-center space-top-20">
    <img class="space-bottom-20" src="/images/common/not-found.png">
    <div>
      <h3>ยังไม่มีการแจ้งเตือน</h3>
    </div>
  </div>

  @endif

</div>

@stop