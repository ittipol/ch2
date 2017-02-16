class Province {
  constructor() {
    this.districtId = null
  }

  load(districtId) {
    this.init();
    this.bind();

    if (typeof districtId != 'undefined') {
      this.districtId = districtId;
    }
  }

  init(){
    this.getDistrict($('#province').val());
  } 

  bind(){

    let _this = this;

    $('#province').on('change',function(){
      _this.getDistrict($(this).val());
    });
  } 

  getDistrict(districtId){

    let _this = this;

    let CSRF_TOKEN = $('input[name="_token"]').val();        

    let request = $.ajax({
      url: "/api/v1/get_district/"+districtId,
      type: "get",
      // data: {_token:CSRF_TOKEN},
      dataType:'json'
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
      $('#district').empty();
      $.each(response, function(key,value) {
        
        let option = $("<option></option>");

        // if(key == _this.districtId){
        //   option.prop('selected',true);
        // }

        $('#district').append(option.attr("value", key).text(value));

      });

      Province.districtId = null;
      
    });

    // Callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown){
      // Log the error to the console
      console.error(
          "The following error occurred: "+
          textStatus, errorThrown
      );
    });

    // Callback handler that will be called regardless
    // if the request failed or succeeded
    request.always(function () {});
  }

}