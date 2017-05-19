<!doctype html>
<html>
<head>
  <!-- Meta data -->
  @include('scripts.meta') 
  <!-- CSS & JS -->
  @include('scripts.script')
  <!--<script type="text/javascript" src="{{ URL::asset('js/node_modules/socket.io.js') }}"></script>-->
  <!-- Title  -->
  @include('page_title')
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

  @include('layouts.blackbox.components.global-search')
  @include('layouts.blackbox.components.global-notification')
  @include('layouts.blackbox.components.global-cart')
  @include('layouts.blackbox.components.modal-dialog')

  @include('layouts.blackbox.components.common')
  
  @include('layouts.blackbox.components.footer')

  <script type="text/javascript">

    $(document).ready(function(){

      const notificationBottom = new NotificationBottom('ขออภัย<br/>เว็บไซต์อยู่ระหว่างการปรับปรุงใช้งาน','','','small',true);
      notificationBottom.load();

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

</body>
</html>