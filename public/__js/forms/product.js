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

        const cart = new GlobalCart(_this.token);
        cart.cartAdd(_this.productId,$('#product_quantity').val());

        setTimeout(function(){_this.allowedClick = true},600);

      }

    });
  
  }

}