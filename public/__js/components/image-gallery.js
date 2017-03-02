class ImageGallery {
  constructor(displayDescription = false) {
    this.images = null;
    this.noImg = '/images/common/no-img.png';
    this.displayDescription = displayDescription;
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

      _this.setImage(_this.images[$(this).data('id')]['_url']);
      _this.setImageDescription(_this.images[$(this).data('id')]['description']);

    });
  
    $(document).on('click','.open-description',function(){
      $('.display-image-description-icon').css('display','none');
      $('.image-description').css('top','0');
    });  

    $('.close-image-description-icon').on('click',function(){
      $('.image-description').css('top','100%');
      $('.display-image-description-icon').css('display','block');
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

    if((imgHeight > frameheight) || (imgWidth > frameWidth)) {

      let diff = Math.abs(frameWidth - imgWidth);

      if(diff < 500) {

      }else if(diff < 1000) {

        // let imgH = Math.ceil(imgHeight * (frameWidth / imgWidth));
        let imgW = Math.ceil(imgWidth * (frameheight / imgHeight));

        if(imgW > frameWidth) {

          $('#image_display').css({
            'height': '100%',
            'margin-left': -((imgW - frameWidth) / 2)
          });

        }

      }

      // if(Math.abs(imgHeight - imgWidth) > 350) {
      //   let imgH = Math.ceil(imgHeight * (frameWidth / imgWidth));

      //   $('#image_display').css({
      //     'width': '100%',
      //     'height': imgH,
      //     'margin-top': (frameheight - imgH) / 2
      //   });
      // }else{
      //   // let imgW = Math.ceil(imgWidth * (frameheight / imgHeight));

      //   // $('#image_display').css({
      //   //   'width': imgW,
      //   //   'height': '100%',
      //   //   'margin-top': 0
      //   // });
      // }

    }else{

      $('#image_display').css({
        'width': 'auto',
        'height': 'auto',
        'margin-top': (frameheight-imgHeight) / 2
      });
      
    }
  }

  setImageDescription(description) {

    if(description == null) {
      description = '-'
    }

    $('#image_description').text(description);
  }

  enableImageDesription() {

    $('.display-image-description-icon').css('display','block');

    $('.image-description').css({
      'display':'block',
      'top':'100%'
    });
  }

}