<?php
  require 'minify/minify-js.php';
  require 'minify/minify-css.php';
?>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk5a17EumB5aINUjjRhWCvC1AgfxqrDQk&libraries=places"></script> -->

<?php
  $combine = false;
?>

<?php

  $jsFiles = array(
    '__js/jquery-3.1.1.min.js',
    '__js/jquery.validate.min.js',
    '__js/blackbox/blackbox.js',
    '__js/library/token.js',
    '__js/map/map.js',
    '__js/forms/form.js',
    '__js/forms/images.js',
    '__js/forms/open-hours.js',
    '__js/forms/district.js',
    '__js/forms/province.js',
    '__js/forms/address.js',
    '__js/forms/tagging.js',
    '__js/forms/text-input-stack.js',
    '__js/forms/period-date.js',
    '__js/forms/real-estate.js',
    '__js/forms/job.js',
    '__js/components/notification-bottom.js',
    '__js/components/additional-option.js',
    '__js/components/custom-scroll.js',
    '__js/components/filter.js',
    '__js/components/image-gallery.js',
    '__js/components/tabs.js'
  );

  if($combine){
    $code = '';
    foreach ($jsFiles as $js) {
      $code .= file_get_contents($js);
    }

    $_js = JSMin::minify($code);

    if(!file_exists(public_path().'/js/8fcf1793a14f7d35.js') || (strlen($_js) != filesize(public_path().'/js/8fcf1793a14f7d35.js'))){
      file_put_contents('js/8fcf1793a14f7d35.js', $_js);
    }
  }
  
?>

@if($combine){
<script type="text/javascript" src="{{ URL::asset('js/8fcf1793a14f7d35.js') }}"></script>
@endif
<script type="text/javascript" src="{{ URL::asset('js/ckeditor/ckeditor.js') }}"></script>

<?php if(!$combine): 
        foreach ($jsFiles as $js) {
?>
  <script type="text/javascript" src="<?php echo '/'.$js; ?>"></script>
<?php 
        }
    endif; 
?>


<?php
  $cssFiles = array(
    '__css/bootstrap.min.css',
    '__css/core.css',
    '__css/map/map.css',
    '__css/components/notification-bottom.css',
    '__css/components/list.css',
    '__css/components/form.css',
    '__css/components/tag.css',
    '__css/components/card.css',
    '__css/components/button.css',
    '__css/components/switch.css',
    '__css/components/additional-option.css',
    '__css/components/custom-scroll.css',
    '__css/components/filter-panel.css',
    '__css/components/box.css',
    '__css/components/pagination.css',
    '__css/components/error.css',
    '__css/components/tile.css',
    '__css/pages/shop.css',
    '__css/pages/account.css',
    '__css/layouts/detail.css',
    '__css/layouts/blackbox/wrapper.css',
    '__css/layouts/blackbox/components/action-bar.css',
    '__css/layouts/blackbox/components/main-nav.css',
    '__css/layouts/blackbox/components/main-panel.css',
    '__css/layouts/blackbox/components/header.css',
    '__css/layouts/blackbox/components/footer.css',
    '__css/layouts/blackbox/responsive.css'
  );

  if($combine){
    $code = '';
    foreach ($cssFiles as $css) {
      $code .= file_get_contents($css);
    }

    $_css = CSSMin::minify($code);

    if(!file_exists(public_path().'/css/a590bf3e950e330b.css') || (strlen($_css) != filesize(public_path().'/css/a590bf3e950e330b.css'))){
      file_put_contents('css/a590bf3e950e330b.css', $_css);
    }
  }

?>

@if($combine){
<link rel="stylesheet" href="{{ URL::asset('css/a590bf3e950e330b.css') }}" />
@endif

<?php if(!$combine): 
        foreach ($cssFiles as $css) {
?>
  <link rel="stylesheet" href="<?php echo '/'.$css; ?>" />
<?php 
        }
    endif; 
?>

<script type="text/javascript">
  CKEDITOR.config.height = '300px';
</script>

@if(Session::has('message.title') && Session::has('message.type'))
<script type="text/javascript">
  $(document).ready(function(){

    let title = '{{ Session::get("message.title") }}';
    let type = '{{ Session::get("message.type") }}';
    let desc = '';
    @if(Session::has('message.desc'))
      desc = '{{ Session::get("message.desc") }}';
    @endif

    const notificationBottom = new NotificationBottom(title,desc,type);
    notificationBottom.load();

  });
</script>
@endif
