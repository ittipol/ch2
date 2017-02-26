@extends('layouts.blackbox.main')
@section('content')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          ประเภทสินค้า
        </div>
      </div>
    </div>
  </div>

  @include('components.form_error') 

  <?php 
    echo Form::model($_formData, [
      'id' => 'main_form',
      'method' => 'PATCH',
      'enctype' => 'multipart/form-data'
    ]);
  ?>

  <?php
    echo Form::hidden('_model', $_formModel['modelName']);
  ?>

  <div class="form-section">


    <div class="form-row">

      <?php 
        echo Form::label('category', 'เลือกประเภทสินค้า');
      ?>
      <div id="category_panel" class="product-category-list"></div>

    </div>

  </div>

  <?php
    echo Form::submit('บันทึก', array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  class ProductCategory {

    contructor(panel) {
      this.panel = panel;
      this.prevId;
      this.currId;
      this.currListGroup;
    }

    load() {
      this.getCategory();
      this.bind();
    }

    bind() {

      let _this = this;

      $(document).on('click','.categoty-next',function(){

        if(typeof _this.currId != 'undefined') {
          _this.prevId = _this.currId;
        }

        _this.currId = $(this).data('id');
        _this.getCategory($(this).data('id'));
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
          console.log('be');

          if(typeof _this.currListGroup != 'undefined') {
            $(_this.currListGroup).css('left','-40%');
          }
        }
      });

      // Callback handler that will be called on success
      request.done(function (response, textStatus, jqXHR){
        _this.createListGroup(response.categories);
      });

      request.fail(function (jqXHR, textStatus, errorThrown){
        console.error(
            "The following error occurred: "+
            textStatus, errorThrown
        );
      });

    }

    createListGroup(categories) {

      let listGroup = document.createElement('div');
      listGroup.setAttribute('class','list-item-group');
      listGroup.style.display = 'none';

      this.currListGroup = listGroup;

      // <div class="list-item">
      //   <a href="http://ch.local/shop/ร้านค้า-1-13/description">
      //     <img class="icon" src="/images/common/pencil.png">
      //     <h4>คำอธิบายร้านค้า</h4>
      //   </a>
      // </div>

      let html = '';
      for (var i = 0; i < categories.length; i++) {
        html += this.createList(categories[i]['id'],categories[i]['name']);
      };

      $('#category_panel').html($(listGroup).html(html));
      $(listGroup).fadeIn(220);

    }

    createList(id,name) {

      let html = '';
      html += '<div class="list-item">';
      html += '<a class="categoty-next" data-id="'+id+'">';
      html += '<h4>'+name+'</h4>';
      html += '</a>';
      html += '</div>';

      return html;

    }

  }

  $(document).ready(function(){

    const productCategory = new ProductCategory('category_panel');
    productCategory.load();

    const form = new Form();
    form.load();
    
  });    
</script>

@stop