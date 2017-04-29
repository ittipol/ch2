class AdditionalOption {

  constructor() {
    this.obj = null;
  }

  load(){
    this.bind();
  }

  bind(){

    let _this = this;
    let token = new Token();

    $('.additional-option').on('click',function(){

      _this.closePrevBox();

      let id = token.generateToken();

      let top = $(this).position().top;
      let left = $(this).position().left;

      let div = document.createElement('div');
      div.setAttribute('class','additional-option-items');
      div.setAttribute('id','additional_option_'+id);
      div.style.top = top+'px';
      div.innerHTML = $(this).find('.additional-option-content').html();

      $(this).parent().append(div);

      $(div).slideDown(220);

      let _left = left - (div.offsetWidth - this.offsetWidth);

      if((_left-20) > 0) {
        div.style.left = _left+'px';
      }else{ console.log('z');
        div.style.left = left+'px';
      }

      // div.style.left = (left - (div.offsetWidth - this.offsetWidth))+'px';
      // div.style.left = left;

      setTimeout(function(){
        _this.obj = div;
      },200);
    });

    $(document).on('click',function(){
      _this.closePrevBox();
    });

    $(window).resize(function() {
      _this.closePrevBox();
    });
  }

  closePrevBox() {
    if(this.obj != null){
      $(this.obj).fadeOut(220);
      this.obj = null;
    }
  }

}
