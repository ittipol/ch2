class Tabs {
  constructor(tab = '') {
    this.currentTab = tab;
    this.prevTab;
  }

  load() {
    // this.init();
    this.bind();

    $('.tab[data-tab="'+this.currentTab+'"]').trigger('click');

  }

  init() {}

  bind() {

    let _this = this;

    $('.tab').on('click',function(){
      _this.showTab($(this).data('tab'));

    });

  }

  showTab(tab) {

    if(this.prevTab) {
      $('#'+this.prevTab).css('display','none');
    }
    
    $('#'+tab).css('display','block');
  
    this.prevTab = tab;
  }

}