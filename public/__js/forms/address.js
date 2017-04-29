class Address {
	constructor() {
		this.subDistrictId = null;
		this.districtId = null;
	}

	load() {
		this.bind();
		this.getDistrict($('#province').val());
	}

	bind(){

	  let _this = this;

	  $('#province').on('change',function(){
	    _this.getDistrict($(this).val());
	  });

	  // $('#district').on('change',function(){
	  //   _this.getSubDistrict($(this).val());
	  // });

	} 

	setDistrictId(districtId) {
		this.districtId = districtId;
	}

	// setSubDistrictId(subDistrictId) {
	// 	this.subDistrictId = subDistrictId;
	// }

	getDistrict(provinceId){

	  let _this = this;

	  // let CSRF_TOKEN = $('input[name="_token"]').val();        

	  let request = $.ajax({
	    url: "/api/v1/get_district/"+provinceId,
	    type: "get",
	    dataType:'json'
	  });

	  // Callback handler that will be called on success
	  request.done(function (response, textStatus, jqXHR){
	    $('#district').empty();
	    $.each(response, function(key,value) {
	      let option = $("<option></option>");

  	    if(key == _this.districtId){
  	      option.prop('selected',true);
  	      _this.districtId = null;
  	    }

	      $('#district').append(option.attr("value", key).text(value));
	    });

	    // _this.getSubDistrict($('#district').val());
	    
	  });

	  request.fail(function (jqXHR, textStatus, errorThrown){
	    console.error(
	        "The following error occurred: "+
	        textStatus, errorThrown
	    );
	  });

	}

	// getSubDistrict(districtId){

	//   let _this = this;

	//   let CSRF_TOKEN = $('input[name="_token"]').val();        

	//   let request = $.ajax({
	//     url: "/api/v1/get_sub_district/"+districtId,
	//     type: "get",
	//     dataType:'json'
	//   });

	//   request.done(function (response, textStatus, jqXHR){

	//     $('#sub_district').empty();

	//     if(Object.keys(response).length > 0) {
	//     	$.each(response, function(key,value) {
	//     	  let option = $("<option></option>");

	//     	  if(key == _this.subDistrictId){
	//     	    option.prop('selected',true);
	//     	    _this.subDistrictId = null;
	//     	  }
	    	  
	//     	  $('#sub_district').append(option.attr("value", key).text(value));
	//     	});	
	//     }else{
	//     	let option = $("<option></option>");
	//     	$('#sub_district').append(option.attr("value", '0').text('-'));
	//     }

	    
	//   });

	//   request.fail(function (jqXHR, textStatus, errorThrown){
	//     console.error(
	//         "The following error occurred: "+
	//         textStatus, errorThrown
	//     );
	//   });
	  
	// }

}