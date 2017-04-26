class ImageGallery {
  constructor(displayDescription = false) {
    this.images = null;
    this.noImg = '/images/common/image.svg';
    this.displayDescription = displayDescription;
    this.currentImage = 0;
    this.totalImage = 0;
    this.descriptionOpened = false;
  }

  load(images = null) {

    if ((images != '[]') && (typeof images[0] != 'undefined')) {

      this.setImage(images[0]['_url']);
      this.setImageDescription(images[0]['description']);
      this.makeGalleryList(images);

      this.images = images;

      this.init();
      this.bind();

    }else{
      $('#image_display').attr('src',this.noImg);
    }

  }

  init() {
    this.enableImageDesription();
  }

  bind() {

    let _this = this;

    $('.preview-image').on('click',function(){

      _this.currentImage = $(this).data('id');

      $('#current_image_description').text(_this.currentImage+1);
   
      _this.setImage(_this.images[$(this).data('id')]['_url']);
      _this.setImageDescription(_this.images[$(this).data('id')]['description']);

    });

    $('#prev_image_description').on('click',function(){
      
      if(--_this.currentImage < 0) {
        _this.currentImage = _this.totalImage;
      }

      $('#current_image_description').text(_this.currentImage+1);

      _this.setImage(_this.images[_this.currentImage]['_url']);
      _this.setImageDescription(_this.images[_this.currentImage]['description']);

    });

    $('#next_image_description').on('click',function(){
      
      if(++_this.currentImage > _this.totalImage) {
        _this.currentImage = 0;
      }

      $('#current_image_description').text(_this.currentImage+1);

      _this.setImage(_this.images[_this.currentImage]['_url']);
      _this.setImageDescription(_this.images[_this.currentImage]['description']);

    });
  
    $(document).on('click','.image-description-display-button',function(){

      if(!_this.descriptionOpened) {
        $('.display-image-description-icon').css('display','none');
        $('.image-description').addClass('opened');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');

        _this.descriptionOpened = true;
      }

    });  

    $('.close-image-description-icon').on('click',function(){

      if(_this.descriptionOpened) {
        $('.display-image-description-icon').css('display','block');
        $('.image-description').removeClass('opened');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');
      
        _this.descriptionOpened = false;
      }

    });

    $('.content-wrapper-overlay').on('click',function(){

      if(_this.descriptionOpened) {
        $('.display-image-description-icon').css('display','block');
        $('.image-description').removeClass('opened');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');

        _this.descriptionOpened = false;
      }

    });

    // $(window).resize(function() {
    //   _this.alignCenter();
    // });

  }

  makeGalleryList(images) {

    let len = images.length;

    for (var i = 0; i < len; i++) {

      let img = document.createElement('img');
      img.src = images[i]['_xs_url'];

      let div = document.createElement('div');
      $(div).addClass('preview-image').data('id',i);
      // $(div).css('background-image','url('+images[i]['_xs_url']+')');
      $(div).append(img);

      $('#image_gallery_list').append(div);

    };

    this.totalImage = (i-1);

    $('#current_image_description').text(1);
    $('#total_image_description').text(i);

  }

  setImage(url) {

    let _this = this;

    let image = new Image();
    image.src = url;

    image.onload = function() {
      _this.alignCenter(image.width,image.height);
      $('#image_display').css('display','inline-block');
    }

    $('#image_display').css('display','none').attr('src',url);

  }

  alignCenter(imgWidth,imgHeight) {

    let frameWidth = Math.ceil($('.image-gallary-panel').width());
    let frameheight = Math.ceil($('.image-gallary-panel').height());

    if(imgHeight > imgWidth) {

      if(imgHeight > frameheight) {

        let imgW = Math.ceil(imgWidth * (frameheight / imgHeight));

        $('#image_display').css({
          'height': '100%',
          'margin-top': '0'
          // 'margin-left': -((imgW - frameWidth) / 2)
        });

      }else if(imgWidth > frameWidth) {

        let imgH = Math.ceil(imgHeight * (frameWidth / imgWidth));

        $('#image_display').css({
          'width': '100%',
          'height': imgH,
          'margin-top': (frameheight - imgH) / 2
        });

      }else{

        $('#image_display').css({
          'width': 'auto',
          'height': 'auto',
          'margin-top': (frameheight-imgHeight) / 2,
          // 'margin-left': (frameWidth-imgWidth) / 2
        });

      }

    }else{

      if(imgWidth > frameWidth) {

        let imgH = Math.ceil(imgHeight * (frameWidth / imgWidth));
        let imgW = Math.ceil(imgWidth * (frameheight / imgHeight));

        if(imgW > frameWidth) {
          $('#image_display').css({
            'height': '100%',
            'margin-left': -((imgW - frameWidth) / 2)
          });
        }else{
          $('#image_display').css({
            'height': '100%',
          });
        }

      }else if(imgHeight > frameheight) {

        let imgW = Math.ceil(imgWidth * (frameheight / imgHeight));

        $('#image_display').css({
          'height': '100%',
          // 'margin-left': -((imgW - frameWidth) / 2)
        });

      }else{

        $('#image_display').css({
          'width': 'auto',
          'height': 'auto',
          'margin-top': (frameheight-imgHeight) / 2,
          // 'margin-left': (frameWidth-imgWidth) / 2
        });

      }

    }

  }

  setImageDescription(description) {

    if(description == null) {
      description = '-'
    }

    $('#image_description').text(description);
  }

  enableImageDesription() {

    if(this.displayDescription) {
      $('.display-image-description-icon').css('display','block');
    }

    // $('.image-description').css({
    //   'display':'block',
    //   'top':'100%'
    // });
  }

}