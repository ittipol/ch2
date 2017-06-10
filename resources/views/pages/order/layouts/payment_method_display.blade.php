<?php
  switch ($paymentMethod['type']) {
    case 'bank-transfer':
    ?>
      @include('pages.order.layouts.payment_method.display_bank_transfer')
    <?php
      break;

    case 'promptpay':
    ?>
      @include('pages.order.layouts.payment_method.display_promptpay')
    <?php
      break;

    case 'paypal':
    ?>
      @include('pages.order.layouts.payment_method.display_paypal')
    <?php
      break;
    
    default:
    ?>
      @include('pages.order.layouts.payment_method.display_default')
    <?php
      break;
  }
?>