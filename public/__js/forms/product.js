class Product {
  
  constructor(token,productId) {
    this.token = token;
    this.productId = productId;
    // this.minimumOrder = minimumOrder;
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

      // let quantity = $('#product_quantity').val();

      // if(_this.minimumOrder > quantity) {

      //   const notificationBottom = new NotificationBottom('ไม่สามารถสั่งซื้อได้','จำนวนการสั่งซื้อของคุณน้อยกว่าจำนวนการสั่งซื้อขั้นต่ำของสินค้านี้','error');
      //   notificationBottom.setDelay(5000);
      //   notificationBottom.load();

      // }else{

      //   const cart = new GlobalCart(_this.token);
      //   cart.cartAdd(_this.productId,quantity);
      // }

    });
  
  }

}