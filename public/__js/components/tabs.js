class Tabs {
  constructor(tab = '') {
    this.currentTab = tab;
    this.prevTab;
  }

  load() {
    this.init();
    this.bind();

    $('.tab[data-tab="person_experience_tab"]').trigger('click');

  }

  init() {

    // $('#notification_panel_trigger').trigger('click');

    // this.showTab(this.currentTab);
  }

  bind() {

    let _this = this;

    $('.tab').on('click',function(){
      // if($(this).is(':checked')) {
      //   _this.showTab($(this).data('tab'));
      // }

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

  // <div class="tabs clearfix">
  //   <label>
  //     <input class="tab" type="radio" name="tabs"  data-tab="item_detail" checked >
  //     <span href="#">รายละเอียดสินค้า</span>
  //   </label>
  //   <label>
  //     <input class="tab" type="radio" name="tabs" data-tab="announcement_detail" >
  //     <span href="#">รายละเอียดเพิ่มเติม</span>
  //   </label>
  // </div>

}