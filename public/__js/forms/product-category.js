class ProductCategory {

  constructor(panel) {
    this.panel = panel;
    this.selectedCat;
    this.prevId = [];
    this.currId;
    this.currListGroup;
    this.level = 0;
    this.allowedClick = true;
  }

  load() {
    this.getCategory();
    this.bind();
  }

  bind() {

    let _this = this;

    $(document).on('click','.nav-next',function(){

      if(!_this.allowedClick) {
        return false;
      }

      _this.allowedClick = false;

      _this.selectedCat = $(this).data('id');

      console.log('selected:'+_this.selectedCat);

      if($(this).data('next')) {

        if(_this.level > 0) {
          _this.prevId.push(_this.currId);
          console.log('Stack:'+_this.prevId);
        }

        _this.currId = $(this).data('id');

        _this.level++;
        _this.getCategory($(this).data('id'));
      }else{
        $(this).parent().addClass('selected');
        
        setTimeout(function(){
          _this.allowedClick = true;
        },500);

      }

    });

    $(document).on('click','.back-button',function(){

      if(!_this.allowedClick) {
        return false;
      }

      _this.allowedClick = false;

      if(--_this.level > 0) {
        // _this.level--;
        _this.currId = _this.prevId.pop();
        _this.getCategory(_this.currId);
      }else{
        _this.level = 0;
        _this.getCategory();
      }

      // if((_this.level-1) == 0) {
      //   _this.level = 0;
      //   _this.getCategory();
      // }else{
      //   _this.level--;
      //   _this.currId = _this.prevId.pop();
      //   _this.getCategory(_this.currId);
      // }

    });

  }

  getCategory(parentId = ''){

    let _this = this;

    let CSRF_TOKEN = $('input[name="_token"]').val();        

    let request = $.ajax({
      url: "/api/v1/get_category/"+parentId,
      type: "get",
      dataType:'json',
      beforeSend: function( xhr ) {

        if(typeof _this.currListGroup != 'undefined') {
          $(_this.currListGroup).fadeOut(220);
        }
      }
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
      _this.createListGroup(response.categories);

      setTimeout(function(){
        _this.allowedClick = true;
      },500);

    });

    // request.fail(function (jqXHR, textStatus, errorThrown){
    //   console.error(
    //       "The following error occurred: "+
    //       textStatus, errorThrown
    //   );
    // });

  }

  createListGroup(categories) {

    let listGroup = document.createElement('div');
    listGroup.setAttribute('class','list-item-group');
    listGroup.style.display = 'none';

    this.currListGroup = listGroup;

    let html = '';

    if(this.level > 0) {
      html += this.createBackBtn();
    }

    for (var i = 0; i < categories.length; i++) {
      html += this.createList(categories[i]['id'],categories[i]['name'],categories[i]['s']);
    };

    $('#'+this.panel).html($(listGroup).html(html));
    $(listGroup).fadeIn(220);

  }

  createList(id,name,next) {

    let html = '';
    html += '<div class="list-item product-category-list-item">';

    if(next) {
      html += '<img class="icon-pos-right" src="/images/icons/next.png" />'
    }else{

    }

    html += '<a class="nav-next" data-next="'+next+'" data-id="'+id+'">';
    html += '<h4>'+name+'</h4>';
    html += '</a>';
    html += '</div>';

    return html;

  }

  createBackBtn() {

    let html = '';
    html += '<div class="list-item product-category-list-item back-button" data-back="true">';
    html += '<img class="icon-pos-left" src="/images/icons/back-white.png" />'
    html += '<a>';
    html += '<h4>กลับ</h4>';
    html += '</a>';
    html += '</div>';

    return html;

  }

}