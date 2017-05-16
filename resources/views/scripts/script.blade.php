<?php
  require 'minify/minify-js.php';
  require 'minify/minify-css.php';
?>

<?php
  $combine = true;
?>

<?php

  // $jsFiles = array(
  //   '__js/jquery-3.1.1.min.js',
  //   '__js/jquery.validate.min.js',
  //   '__js/blackbox/blackbox.js',
  //   '__js/library/token.js',
  //   '__js/map/map.js',
  //   '__js/forms/form.js',
  //   '__js/forms/images.js',
  //   '__js/forms/attached-file.js',
  //   '__js/forms/open-hours.js',
  //   '__js/forms/product-category.js',
  //   '__js/forms/address.js',
  //   '__js/forms/tagging.js',
  //   '__js/forms/text-input-list.js',
  //   '__js/forms/input-field-group-list.js',
  //   '__js/forms/period-date.js',
  //   '__js/forms/real-estate.js',
  //   '__js/forms/job.js',
  //   '__js/forms/product.js',
  //   '__js/forms/product-discount.js',
  //   '__js/forms/shipping-method.js',
  //   '__js/forms/product-option-value.js',
  //   '__js/components/input-field.js',
  //   '__js/components/notification-bottom.js',
  //   '__js/components/additional-option.js',
  //   '__js/components/custom-scroll.js',
  //   '__js/components/image-gallery.js',
  //   '__js/components/tabs.js',
  //   '__js/components/profile-image.js',
  //   '__js/components/global-cart.js',
  //   '__js/components/modal-dialog.js',
  //   '__js/components/order-progress-bar.js',
  //   '__js/components/right-side-panel.js',
  //   '__js/components/filter.js',
  //   '__js/components/job-applying-message.js',
  //   '__js/notification/push-notification.js',
  // );

  // if($combine){
  //   $code = '';
  //   foreach ($jsFiles as $js) {
  //     $code .= file_get_contents($js);
  //   }

  //   $_js = JSMin::minify($code);

  //   if(!file_exists(public_path().'/js/8fcf1793a14f7d35.js') || (strlen($_js) != filesize(public_path().'/js/8fcf1793a14f7d35.js'))){
  //     file_put_contents('js/8fcf1793a14f7d35.js', $_js);
  //   }
  // }
  
?>

@if($combine)
<script type="text/javascript" src="{{ URL::asset('js/8fcf1793a14f7d35.js') }}"></script>
@endif

@if(env('APP_ENV') == 'production')
  <script type="text/javascript" src="{{ URL::asset('js/analyticstracking.js') }}"></script>
@endif

@if(!$combine)
@foreach ($jsFiles as $js)
  <script type="text/javascript" src="/{{$js}}"></script>
@endforeach
@endif


<?php

  // $cssFiles = array(
  //   '__css/bootstrap.min.css',
  //   '__css/core.css',
  //   '__css/map/map.css',
  //   '__css/components/loading.css',
  //   '__css/components/notification-bottom.css',
  //   '__css/components/list.css',
  //   '__css/components/form.css',
  //   '__css/components/tag.css',
  //   '__css/components/card.css',
  //   '__css/components/button.css',
  //   '__css/components/switch.css',
  //   '__css/components/additional-option.css',
  //   '__css/components/custom-scroll.css',
  //   '__css/components/choice-box.css',
  //   '__css/components/pagination.css',
  //   '__css/components/box.css',
  //   '__css/components/image-gallery.css',
  //   '__css/components/tile.css',
  //   '__css/components/message.css',
  //   '__css/components/error.css',
  //   '__css/components/modal-dialog.css',
  //   '__css/components/right-side-panel.css',
  //   '__css/components/sub-header.css',
  //   '__css/components/breadcrumb.css',
  //   '__css/components/attached-file.css',
  //   '__css/components/tab.css',
  //   '__css/components/notice.css',
  //   '__css/components/timeline.css',
  //   '__css/pages/home.css',
  //   '__css/pages/shop.css',
  //   '__css/pages/account.css',
  //   '__css/pages/detail.css',
  //   '__css/pages/search.css',
  //   '__css/pages/cart.css',
  //   '__css/pages/checkout.css',
  //   '__css/pages/shelf.css',
  //   '__css/layouts/blackbox/wrapper.css',
  //   '__css/layouts/blackbox/components/action-bar.css',
  //   '__css/layouts/blackbox/components/main-nav.css',
  //   '__css/layouts/blackbox/components/content-wrapper.css',
  //   '__css/layouts/blackbox/components/global-panel.css',
  //   '__css/layouts/blackbox/components/global-account.css',
  //   '__css/layouts/blackbox/components/global-notification-panel.css',
  //   '__css/layouts/blackbox/components/global-search-panel.css',
  //   '__css/layouts/blackbox/components/global-cart-panel.css',
  //   '__css/layouts/blackbox/components/global-additional-nav-panel.css',
  //   '__css/layouts/blackbox/components/header.css',
  //   '__css/layouts/blackbox/components/footer.css',
  //   '__css/layouts/blackbox/responsive.css'
  // );

  // if($combine){
  //   $code = '';
  //   foreach ($cssFiles as $css) {
  //     $code .= file_get_contents($css);
  //   }

  //   $_css = CSSMin::minify($code);

  //   if(!file_exists(public_path().'/css/a590bf3e950e330b.css') || (strlen($_css) != filesize(public_path().'/css/a590bf3e950e330b.css'))){
  //     file_put_contents('css/a590bf3e950e330b.css', $_css);
  //   }
  // }

?>

@if($combine)
<link rel="stylesheet" href="{{ URL::asset('css/a590bf3e950e330b.css') }}" />
@endif

@if(!$combine)
@foreach ($cssFiles as $css)
  <link rel="stylesheet" href="/{{$css}}" />
@endforeach
@endif

@if(Session::has('message.title') && Session::has('message.type'))
<script type="text/javascript">
  $(document).ready(function(){
    
    let desc = '';
    @if(Session::has('message.desc'))
      desc = '{{ Session::get("message.desc") }}';
    @endif

    const notificationBottom = new NotificationBottom('{{ Session::get("message.title") }}',desc,'{{ Session::get("message.type") }}');
    notificationBottom.load();

  });
</script>
@endif
