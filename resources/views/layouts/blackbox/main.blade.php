<!doctype html>
<html>
<head>
  <!-- Meta data -->
  @include('scripts.meta') 
  <!-- CSS & JS -->
  @include('scripts.script')
  <script type="text/javascript" src="{{ URL::asset('js/node_modules/socket.io.js') }}"></script>
  <!-- Title  -->
  @if(!empty($_page_title))
  <title>{{$_page_title}}</title>
  @else
  <title>Sunday Square | จุดเริ่มต้นสำหรับคุณ</title>
  @endif
</head>
<body>

  @include('layouts.blackbox.components.global-header')
  @include('layouts.blackbox.components.global-nav')

  <div id="container">
    @include('layouts.blackbox.components.content-wrapper')
  </div>

  @if (Auth::check())
    @include('layouts.blackbox.components.global-account')
  @endif

  @include('layouts.blackbox.components.global-additional-nav')
  @include('layouts.blackbox.components.global-search')
  @include('layouts.blackbox.components.global-notification')
  @include('layouts.blackbox.components.global-cart')
  @include('layouts.blackbox.components.modal-dialog')

  @include('layouts.blackbox.components.common')
  
  @include('layouts.blackbox.components.footer')

  <script type="text/javascript">

    $(document).ready(function(){

      const blackbox = new Blackbox;
      blackbox.load();

      const additionalOption = new AdditionalOption();
      additionalOption.load();

      const rightSidePanel = new RightSidePanel();
      rightSidePanel.load();

      const modelDialog = new ModelDialog();
      modelDialog.load();

      const globalCart = new GlobalCart('{{ csrf_token() }}');
      globalCart.load();

      const inputField = new InputField();
      inputField.load();

      @if(Auth::check())
        const pushNotification = new PushNotification({{Session::get("Person.id") }},'{{ Session::get("Person.token") }}')
        pushNotification.load();
      @endif

      setTimeout(function(){
        $(".nano").nanoScroller();
      },1000);
    });
    
  </script>

  @if (!Auth::check())

    <script type="text/javascript">
      $(document).ready(function(){
        let title = 'เข้าถึงเนื้อหาทั้งหมดและบริษัทหรือร้านค้าด้วยการเข้าสู่ระบบ';
        let desc = '';
        desc += '<div class="button-group">';
        desc += '<a class="button" href="{{URL::to("login")}}">เข้าสู่ระบบ</a>';
        desc += '<a class="button" href="{{URL::to("register")}}">สมัครสมาชิก</a>';
        desc += '</div>';

        const notificationBottom = new NotificationBottom(title,desc,'','medium',true);
        notificationBottom.load();
      });
    </script>
  @endif

</body>
</html>