@extends('layouts.blackbox.main')
@section('content')

  <div class="container space-top-30">

    <h3 class="space-bottom-50">{{$title}}</h3>

    <div class="row">

      <div class="col-xs-12">

        <div class="overflow-hidden">

          @include($page)

        </div>

      </div>

    </div>

  </div>

@stop