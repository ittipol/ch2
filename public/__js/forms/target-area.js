class TargetArea {

	constructor() {}

	load() {
	  this.bind();
	}

	bind() {

	  let _this = this;

	  $('.target-area-chkbox').on('click',function(){
	    if($(this).is(':checked')) {
	      _this.createTargetAreaTag($(this).val(),$(this).data('name'));
	    }else{
	      _this.removeTargetAreaTag($(this).val());
	    }
	  });

	  // $('#selected_target_area').on('click','.district-target-btn',function(){
	  //   _this.getDistrict($(this).data('province'));
	  // });

	}

	setTags(tagJson) {

		if (typeof tagJson != 'undefined'){

			for (let i = 0; i < tagJson['province_id'].length; i++) {

				let obj = $('#province_area_chkbox_'+tagJson['province_id'][i]);

				if(obj.is(':checked')) {
				  this.createTargetAreaTag(tagJson['province_id'][i],obj.data('name'));
				}else{
					obj.trigger('click');
				}
				
			}

		}
	}

	createTargetAreaTag(id,label) {

		let _this = this;

	  let tagChip = document.createElement('span');
	  tagChip.setAttribute('class','tag-chip s2');
	  tagChip.setAttribute('id','province_tag_'+id);

	  let tagNameElem = document.createElement('span');
	  tagNameElem.setAttribute('class','tag-name');
	  tagNameElem.innerHTML = label;

	  let tagDelete = document.createElement('span');
	  tagDelete.setAttribute('class','tag-delete-chip');
	  tagDelete.innerHTML = '×';

	  tagDelete.addEventListener("click", function(e){

	    if($('#province_area_chkbox_'+id).is(':checked')) {
	      $('#province_area_chkbox_'+id).trigger('click');
	    }

	    $(this).parent().remove();
	    // _this.removeTargetAreaTag(id);

	  }, false);

	  tagChip.appendChild(tagNameElem);
	  tagChip.appendChild(tagDelete);

	  document.getElementById('selected_target_area').appendChild(tagChip);

	//   let wrapper = document.createElement('div');
	//   wrapper.setAttribute('class','target-area-content space-bottom-10');
	//   wrapper.setAttribute('id','target_area_content_'+id);

	//   let subTargetWrapper = document.createElement('div');
	//   subTargetWrapper.setAttribute('id','sub_target_area_'+id);

	//   let subTarget = document.createElement('div');
	//   subTarget.setAttribute('id','sub_target_area_content_'+id);

	//   let a = document.createElement('a');
	//   a.setAttribute('data-right-side-panel',1);
	//   a.setAttribute('data-right-side-panel-target','#sub_target_area_panel');
	//   a.setAttribute('class','district-target-btn');
	//   a.setAttribute('data-province',id);
	//   a.innerHTML = 'ระบุอำเภอ';

	//   subTargetWrapper.appendChild(a);
	//   subTargetWrapper.appendChild(subTarget);

	//   wrapper.appendChild(tagChip);
	//   wrapper.appendChild(subTargetWrapper);

	//   document.getElementById('selected_target_area').appendChild(wrapper);
	}

	removeTargetAreaTag(id) {
	  // $('#target_area_content_'+id).remove();
	  $('#province_tag_'+id).remove();
	}

	getDistrict(provinceId){

	  let _this = this;

	  let request = $.ajax({
	    url: "/api/v1/get_district/"+provinceId,
	    type: "get",
	    dataType:'json'
	  });

	  request.done(function (response, textStatus, jqXHR){

	  	// input_name 'TargetArea['+province_id+'][district_id][]
	  	// input_name 'TargetArea['+province_id+'][sub_district_id][]

	  	let html = '<div class="row">';
	  	
	    $.each(response, function(key,value) {

	      // if(key == _this.districtId){
	      //   option.prop('selected',true);
	      //   _this.districtId = null;
	      // }

	      html += '<div class="col-lg-4 col-sm-6 col-xs-12">';
	      html += _this.createChkBox('district',key,value);
	      html += '</div>';

	    });

	    html += '</div>';

	    // $('#sub_target_area').html(html);
	    
	  });

	  request.fail(function (jqXHR, textStatus, errorThrown){
	    console.error(
	        "The following error occurred: "+
	        textStatus, errorThrown
	    );
	  });

	}

	createChkBox(area,id,name) {

		let html = '<label class="choice-box">';
		html += '<input id="'+area+'_area_chkbox_'+id+'" class="target-area-chkbox" data-name="'+name+'" type="checkbox" value="'+id+'">';              
		html += '<div class="inner">'+name+'</div>';
		html += '</label>';

		return html;

	}

}