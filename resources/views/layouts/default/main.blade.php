<!doctype html>
<html>
<head>
  <!-- Meta data -->
  @include('scripts.meta') 
  <!-- CSS & JS -->
  @include('scripts.script')
  <link rel="stylesheet" href="{{ URL::asset('__css/layouts/default/header.css') }}" />
  <link rel="stylesheet" href="{{ URL::asset('__css/layouts/default/footer.css') }}" />
  <link rel="stylesheet" href="{{ URL::asset('__css/pages/user/register.css') }}" />
  <link rel="stylesheet" href="{{ URL::asset('__css/pages/user/login.css') }}" />
  <link rel="stylesheet" href="{{ URL::asset('__css/pages/user/core.css') }}" />
  <!-- Title  -->
  @include('page_title')
</head>
<body>

	@yield('content')

  <script type="text/javascript">

    $(document).ready(function(){

      const notificationBottom = new NotificationBottom();
      notificationBottom.load();

      @if(Session::has('message.title') && Session::has('message.type'))
  
        let _desc = '';
        @if(Session::has('message.desc'))
          _desc = '{{ Session::get("message.desc") }}';
        @endif

        // const notificationBottom = new NotificationBottom();
        notificationBottom.setTitle('{{ Session::get("message.title") }}');
        notificationBottom.setDesc('{{ Session::get("message.desc") }}');
        notificationBottom.setType('{{ Session::get("message.type") }}');
        notificationBottom.display();

      @endif

    });
    
  </script>

</body>
</html>