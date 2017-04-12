class Form {
	constructor(form = '#main_form') {
		this.form = form;
	}

	load() {
		this.init();
		this.bind();
	}

	init() {
	  $(this.form).find('input').keydown(function(event){
  	  if(event.keyCode == 13) {
  	    event.preventDefault()
  	    return false;
  	  }
  	});
	}

	bind() {

		let _this = this;

		$(_this.form).on('submit',function(){

			if(($('#birth_day').length > 0) && ($('#birth_month').length > 0) && ($('#birth_year').length > 0)) {
				var input = document.createElement("input");
				input.setAttribute("type", "hidden");
				input.setAttribute("name", "birth_date");
				input.setAttribute("value", $('#birth_year').val()+'-'+$('#birth_month').val()+'-'+$('#birth_day').val());

				this.appendChild(input);
			}

			$(_this.form).find('input[type="submit"]').prop('disabled','disabled').addClass('disabled');
		
		});

	}
}