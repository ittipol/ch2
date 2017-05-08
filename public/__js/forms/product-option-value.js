class ProductOptionValue {

  constructor() {}

  load() {

    if($('#use_price_chkbox').is(':checked')) {
      $('#price_input').prop('disabled',false);
    }else{
      $('#price_input').prop('disabled',true);
    }

    if($('#use_quantity_chkbox').is(':checked')) {
      $('#quantity_input').prop('disabled',false);
    }else{
      $('#quantity_input').prop('disabled',true);
    }

    this.bind();
  }

  bind() {

    $('#use_price_chkbox').on('click',function(){
      if($(this).is(':checked')) {
        $('#price_input').prop('disabled',false);
      }else{
        $('#price_input').prop('disabled',true).val('');
      }
    });

    $('#use_quantity_chkbox').on('click',function(){
      if($(this).is(':checked')) {
        $('#quantity_input').prop('disabled',false);
      }else{
        $('#quantity_input').prop('disabled',true).val('');
      }
    });

  }

}