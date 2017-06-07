<?php
  switch ($paymentMethod['type']) {
    case 'bank-transfer':
    ?>
      @include('pages.payment_method.layouts.display_bank_transfer')
    <?php
      break;

    case 'promptpay':
    ?>
      @include('pages.payment_method.layouts.display_promptpay')
    <?php
      break;

    case 'paypal':
    ?>
      @include('pages.payment_method.layouts.display_paypal')
    <?php
      break;
    
    default:
    ?>
      @include('pages.payment_method.layouts.display_default')
    <?php
      break;
  }
?>