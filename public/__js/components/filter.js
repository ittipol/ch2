class Filter {
  constructor() {
    this.filterPanelOriginalTop;
    this.filterPanelOriginalHeight;
    this.filterPanelExtendHeight;
  }

  load() {
    this.bind();
    this.setLayout();
  }

  bind() {

    let _this = this;

    $(window).resize(function() {

      let w = window.innerWidth;
      let h = window.innerHeight;

      $('#filter_panel').css({
        height: h - 60
      });

      if(w > 992) {

        if($('#filter_panel_trigger').is(':checked')) {
          $('#filter_panel_trigger').trigger('click');
        }

        $('#filter_panel').css({
          height: h - $('#filter_panel').offset().top
        });
      }

    });

    $('.main-panel-overlay').on('click',function(){
      if($('#filter_panel_trigger').is(':checked')) {
        $('#filter_panel_trigger').trigger('click');
      }
    });

    $('#filter_panel_trigger').on('click',function(){
      if($(this).is(':checked')) {
        $('#filter_panel').addClass('is-filter-panel-open');
        $('.main-panel-overlay').addClass('isvisible');
      }else{
        $('#filter_panel').removeClass('is-filter-panel-open');
        $('.main-panel-overlay').removeClass('isvisible');
      }
    }); 

    $(".nano").on("update", function(event, vals){ 
       // console.log("pos=" + vals.position + ", direction=" + vals.direction + "\n" )

       if(vals.position > _this.filterPanelOriginalTop) {
        $('#filter_panel').css({
          top: vals.position,
          height: _this.filterPanelExtendHeight
        });
       }else{
        $('#filter_panel').css({
          top: _this.filterPanelOriginalTop,
          height: _this.filterPanelOriginalHeight
        });
       }

    });

    // $(".nano").bind("scrolltop", function(e){
    // });

    // $(".nano").bind("scrollend", function(e){
    // });

    $('#filter_panel .sorting-option-radio').on('click',function(){
      if($(this).is(':checked')) {
        $('#sorting_form').submit();
      }
    });
  }

  setLayout() {
    
    let w = window.innerWidth;
    let h = window.innerHeight;

    this.filterPanelOriginalTop = $('#filter_panel').position().top;
    this.filterPanelOriginalHeight = h - $('#filter_panel').offset().top;
    this.filterPanelExtendHeight = this.filterPanelOriginalHeight + this.filterPanelOriginalTop;

    $('#filter_panel').css({
      height: this.filterPanelOriginalHeight
    });

  }

}