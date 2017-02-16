class RealEstate {
  constructor() {}

  load() {
    this.bind();
  }

  bind() {

    let _this = this;

    $('.home-area').on('keydown',function(e){

      if(((e.keyCode < 96) || (e.keyCode > 105)) && ((e.keyCode < 48) || (e.keyCode > 57)) && (e.keyCode != 8) && (e.keyCode != 110) && (e.keyCode != 190)) {
        e.preventDefault();
        return false;
      }

    });

    $('.land-area').on('keydown',function(e){

      if(((e.keyCode < 96) || (e.keyCode > 105)) && ((e.keyCode < 48) || (e.keyCode > 57)) && (e.keyCode != 8) && (e.keyCode != 110) && (e.keyCode != 190)) {
        e.preventDefault();
        return false;
      }
      
      let obj = this;

      clearTimeout(_this.handle);
      _this.handle = setTimeout(function(){
        _this.calSqm($(obj).attr('id'));
      },500);
    });

    $('.indoor').on('keydown',function(e){

      if(((e.keyCode < 96) || (e.keyCode > 105)) && ((e.keyCode < 48) || (e.keyCode > 57)) && (e.keyCode != 8) && (e.keyCode != 110) && (e.keyCode != 190)) {
        e.preventDefault();
        return false;
      }

    });

  }

  calSqm(unit) {

    if(unit == 'sqm') {
      $('#rai').val('');
      $('#ngan').val('');
      $('#wa').val('');
    }else{
      let rai = $('#rai').val() * 1600;
      let ngan = $('#ngan').val() * 400;
      let wa = $('#wa').val() * 4;

      $('#sqm').val(rai+ngan+wa);
    }

  }

}