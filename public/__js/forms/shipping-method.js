class ShippingMethod {

  constructor() {}

  load() {

    if($('.shipping-service-cost-type:checked').val() != 2) {
      $('.service-cost').prop('disabled',true);
    }

    this.bind();
  }

  bind() {

    $('.shipping-service-cost-type').on('change',function(){
   
      if($(this).val() == 2) {
        $('.service-cost').prop('disabled',false);
      }else{
        $('.service-cost').prop('disabled',true);
      }

    });

  }

}