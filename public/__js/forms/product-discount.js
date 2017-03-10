class ProductDiscount {

  constructor(price) {
    this.price = price;
    this.handle;
  }

  load() {

    let _this = this;

    if($('#price_chkbox').is(':checked')) {
      $('#price_text').prop('disabled', false);
      $('#percent_text').prop('disabled', true);

      if($('#price_text').val() != '') {
        let discount = _this.calByPrice($('#price_text').val());
        _this.updateDiscount(discount);
      }

    }

    if($('#percent_chkbox').is(':checked')) {
      $('#percent_text').prop('disabled', false);
      $('#price_text').prop('disabled', true);

      if($('#percent_text').val() != '') {
        let discount = _this.calByPercent($('#percent_text').val());
        _this.updateDiscount(discount);
      }
      
    }

    this.bind();
  }

  bind() {

    let _this = this;

    $('#main_form').on('submit',function(){

      var input = document.createElement('input');
      input.setAttribute('type', 'hidden');
      input.setAttribute('name', 'date_start');
      input.setAttribute('value', 
        $('#promotion_start_year').val()+
        '-'+
        $('#promotion_start_month').val()+
        '-'+
        $('#promotion_start_day').val()+
        ' '+
        $('#promotion_start_hour').val()+
        ':'+
        $('#promotion_start_min').val()+
        ':'+
        '00'
      );
      this.appendChild(input);

      var input = document.createElement('input');
      input.setAttribute('type', 'hidden');
      input.setAttribute('name', 'date_end');
      input.setAttribute('value', 
        $('#promotion_end_year').val()+
        '-'+
        $('#promotion_end_month').val()+
        '-'+
        $('#promotion_end_day').val()+
        ' '+
        $('#promotion_end_hour').val()+
        ':'+
        $('#promotion_end_min').val()+
        ':'+
        '59'
      );
      this.appendChild(input);

    });

    $('#price_chkbox').on('change',function(){

      clearTimeout(_this.handle);

      $('#price_text').prop('disabled', false);
      $('#percent_text').prop('disabled', true);

      if($('#price_text').val() != '') {
        let discount = _this.calByPrice($('#price_text').val());
        _this.updateDiscount(discount);
      }else{
        $('#reduced_amount').text('-');
        $('#reduced_price').text('-');
      }

    });

    $('#percent_chkbox').on('change',function(){

      clearTimeout(_this.handle);

      $('#percent_text').prop('disabled', false);
      $('#price_text').prop('disabled', true);

      if($('#percent_text').val() != '') {
        let discount = _this.calByPercent($('#percent_text').val());
        _this.updateDiscount(discount);
      }else{
        $('#reduced_amount').text('-');
        $('#reduced_price').text('-');
      }

    });

    $('#price_text').on('keyup',function(){

      if($(this).val() < 1) {
        $(this).val('');
      }

      if($(this).val() > _this.price) {
        $(this).val(_this.price);
      }

      if($(this).val() != '') {
        let price = $(this).val();

        clearTimeout(_this.handle);
        _this.handle = setTimeout(function(){
          _this.updateDiscount(_this.calByPrice(price));
        },350);
      }

    });

    $('#percent_text').on('keyup',function(){

      if($(this).val() < 1) {
        $(this).val('');
      }

      if($(this).val() > 100) {
        $(this).val(100);
      }

      if($(this).val() != '') {
        let percent = $(this).val();

        clearTimeout(_this.handle);
        _this.handle = setTimeout(function(){
          _this.updateDiscount(_this.calByPercent(percent));
        },350);
      }

    });

  }

  calByPrice(price) {
    return Math.round(this.price - price,2);
  }

  calByPercent(percent) {
    return Math.round(this.price - ((this.price * percent) / 100),2);
  }

  updateDiscount(discount) {
    $('#reduced_amount').text('THB '+(this.price - discount));
    $('#reduced_price').text('THB '+discount);
  }

}