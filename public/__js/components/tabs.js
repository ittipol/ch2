class Tabs {
  constructor(tab = '') {
    this.currentTab = tab;
  }

  load() {
    this.init();
    this.bind();
  }

  init() {
    this.showTab(this.currentTab);
  }

  bind() {

    let _this = this;

    $('.tab').on('click',function(){
      if($(this).is(':checked')) {
        _this.showTab($(this).data('tab'));
      }
    });

  }

  showTab(tab) {
    $('.tab-content').css('display','none');
    $('#'+tab).css('display','block');
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