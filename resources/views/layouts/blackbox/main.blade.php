<!doctype html>
<html>
<head>
  <!-- Meta data -->
  @include('scripts.meta') 
  <!-- CSS & JS -->
  @include('scripts.script')
  <!-- Title  -->
  <title>Chonburi Square</title>
</head>
<body>

  @include('layouts.blackbox.components.global-header')

  @include('layouts.blackbox.components.global-search')
  @include('layouts.blackbox.components.global-nav')
  @include('layouts.blackbox.components.global-cart')

  <div id="container">
    @include('layouts.blackbox.components.content-wrapper')
  </div>
  <div id="loading_icon" class="loading"></div>
  <div class="global-overlay"></div>

  @include('layouts.blackbox.components.footer')

  <script type="text/javascript">

    $(document).ready(function(){

      const additionalOption = new AdditionalOption();
      additionalOption.load();

      const blackbox = new Blackbox;
      blackbox.load();

      const globalCart = new GlobalCart('{{ csrf_token() }}');
      globalCart.load();

      const inputField = new InputField;
      inputField.load();

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
        desc += '<a class="button" href="{{URL::to("select_registation")}}">สมัครสมาชิก</a>';
        desc += '</div>';

        const notificationBottom = new NotificationBottom(title,desc,'','medium',true);
        notificationBottom.load();
      });
    </script>
  @endif

</body>
</html>