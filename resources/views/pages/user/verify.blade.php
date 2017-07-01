@extends('layouts.default.main')
@section('content')
<script type="text/javascript">

  class VerifyForm {

    constructor() {}

    load() {
      this.bind();
    }

    bind() {

      $('form').submit(function(){


      });

    }

  }

  $(document).ready(function(){

    const verifyForm = new VerifyForm();
    verifyForm.load();

  });

</script>

@stop