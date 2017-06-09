class Product {
  
  constructor(productId) {
    this.productId = productId;
    this.allowedClick = true;
  }

  load() {
    this.bind();
  }

  bind() {

    let _this = this;

    $('#add_to_cart_button').on('click',function(){

      if(_this.allowedClick) {

        _this.allowedClick = false;

        let productOption = null;
        if(typeof $('.product-option-rdobox:checked').val() != 'undefined') {

          // let data = [];
          // data.push($('.product-option-rdobox:checked').val());
          // productOption = JSON.stringify(data);
          productOption = $('.product-option-rdobox:checked').val();

        }
        const cart = new GlobalCart();
        cart.cartAdd(_this.productId,$('#product_quantity').val(),productOption);

        setTimeout(function(){_this.allowedClick = true},600);

      }

    });

    $('.product-option-rdobox').on('change',function(){
      $('#_price').text($(this).data('price'));
      $('#_quantity_text').text($(this).data('quantity-text'));
    });
  
  }

}