class Product {

  constructor() {
    this.quantity;
  }

  load() {

    this.quantity = $('#quantity_input_box').val();

    if($('#unlimited_quantity_chkbox').is(':checked')) {
      $('#quantity_input_box').prop('disabled','disabled');
      $('#quantity_input_box').val('ไม่จำกัดจำนวน');
    }

    this.bind();
  }

  bind() {

    let _this = this;

    $('#unlimited_quantity_chkbox').on('click',function(){
      
      if($(this).is(':checked')) {
        $('#quantity_input_box').prop('disabled','disabled');
        $('#quantity_input_box').val('ไม่จำกัดจำนวน');
      }else{
        $('#quantity_input_box').prop('disabled', false);
        $('#quantity_input_box').val(_this.quantity);
      }

    });

  }

}