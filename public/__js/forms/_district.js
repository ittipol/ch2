class District {
  constructor() {
    this.subDistrictId = null
  }

  load(subDistrictId) {
    this.init();
    this.bind();

    if (typeof subDistrictId != 'undefined') {
      this.subDistrictId = subDistrictId;
    }
  }

  init(){
    this.getSubDistrict($('#district').val());
  } 

  bind(){

    let _this = this;

    $('#district').on('change',function(){
      _this.getSubDistrict($(this).val());
    });
  } 

  getSubDistrict(districtId){

    let _this = this;

    let CSRF_TOKEN = $('input[name="_token"]').val();        

    let request = $.ajax({
      url: "/api/v1/get_sub_district/"+districtId,
      type: "get",
      dataType:'json'
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
      $('#sub_district').empty();
      $.each(response, function(key,value) {
        
        let option = $("<option></option>");

        // if(key == _this.subDistrictId){
        //   option.prop('selected',true);
        // }

        $('#sub_district').append(option.attr("value", key).text(value));

      });

      District.subDistrictId = null;
      
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