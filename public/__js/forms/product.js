class Product {
  
  constructor(token,productId) {
    this.token = token;
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
        if(typeof $('.product-option-value:checked').val() != 'undefined') {

          // let data = [];
          // data.push($('.product-option-value:checked').val());
          // productOption = JSON.stringify(data);
          productOption = $('.product-option-value:checked').val();

        }

        const cart = new GlobalCart(_this.token);
        cart.cartAdd(_this.productId,$('#product_quantity').val(),productOption);

        setTimeout(function(){_this.allowedClick = true},600);

      }

    });
  
  }

}