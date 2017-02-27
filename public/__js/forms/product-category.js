class ProductCategory {

  constructor(panel) {
    this.panel = panel;
    this.selectedElem;
    this.selectedCat;
    this.catPathName = [];
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

    $(document).on('click','.has-next',function(){

      if(!_this.allowedClick) {
        return false;
      }

      _this.allowedClick = false;

      _this.selectedCat = $(this).data('id');

      if(_this.level > 0) {
        _this.prevId.push(_this.currId);
      }

      _this.addCatPath($(this).data('name'));

      _this.currId = $(this).data('id');

      _this.level++;
      _this.getCategory($(this).data('id'));

    });

    $(document).on('click','.has-end',function(){

      if(!_this.allowedClick) {
        return false;
      }

      _this.allowedClick = false;

      _this.selectedCat = $(this).data('id');
      _this.addCatPath($(this).data('name'));

      if(typeof _this.selectedElem != 'undefined') {
       $( _this.selectedElem).removeClass('selected');
      }

      $(this).addClass('selected');
      _this.selectedElem = $(this);
      
      setTimeout(function(){
        _this.allowedClick = true;
      },400);

    });

    $(document).on('click','.back-button',function(){

      if(!_this.allowedClick) {
        return false;
      }

      _this.allowedClick = false;

      if(--_this.level > 0) {
        _this.currId = _this.prevId.pop();
        _this.getCategory(_this.currId);
      }else{
        _this.level = 0;
        _this.getCategory();
      }

    });

  }

  addCatPath(name) {

    if(this.catPathName.length > this.level) {
      this.catPathName.splice(this.level,this.catPathName.length - this.level);
    }

    this.catPathName.push(name);

    let path = '';
    for (var i = 0; i < this.catPathName.length; i++) {

      if(i > 0) {
        path += ' / ';
      }

      if(i == (this.catPathName.length-1)) {
        path += '<span>'+this.catPathName[i]+'</span>';
      }else{
        path += this.catPathName[i];
      }

      
      
    };

    $('#category_selected').html(path);
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

          let currListGroup = $(_this.currListGroup);
          currListGroup.fadeOut(220);

          setTimeout(function(){
            currListGroup.remove();
          },220);

        }
      }
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
      _this.createListGroup(response.categories);

      setTimeout(function(){
        _this.allowedClick = true;
      },400);

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

    if(this.level > 0) {
      listGroup.append(this.createBackBtn());
    }

    for (var i = 0; i < categories.length; i++) {
      listGroup.append(this.createList(categories[i]['id'],categories[i]['name'],categories[i]['s']));
    };

    $('#'+this.panel).append(listGroup);
    $(listGroup).fadeIn(220);

  }

  createList(id,name,next) {

    let list = document.createElement('div');

    let cssClass = 'list-item product-category-list-item'; 

    if(this.selectedCat == id) {
      cssClass += ' selected';
      this.selectedElem = list;
    }

    if(next) {
      cssClass += ' has-next';
    }else{
      cssClass += ' has-end';
    }

    list.setAttribute('class',cssClass);
    list.setAttribute('data-id',id);
    list.setAttribute('data-name',name);

    list.innerHTML = '<h4>'+name+'</h4>';

    return list;

  }

  createBackBtn() {

    let btn = document.createElement('div');
    btn.setAttribute('class','list-item product-category-list-item back-button');

    let html = '';
    html += '<img class="icon-pos-left" src="/images/icons/back-white.png" />'
    html += '<a>';
    html += '<h4>กลับ</h4>';
    html += '</a>';

    btn.innerHTML = html;

    return btn;

  }

}