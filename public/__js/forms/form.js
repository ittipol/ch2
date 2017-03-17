class Form {
	constructor() {}

	load() {
		this.init();
		this.bind();
	}

	init() {
	  $('form#main_form input').keydown(function(event){
  	  if(event.keyCode == 13) {
  	    event.preventDefault()
  	    return false;
  	  }
  	});
	}

	bind() {

		$('#main_form').on('submit',function(){

			if(($('#birth_day').length > 0) && ($('#birth_month').length > 0) && ($('#birth_year').length > 0)) {
				var input = document.createElement("input");
				input.setAttribute("type", "hidden");
				input.setAttribute("name", "birth_date");
				input.setAttribute("value", $('#birth_year').val()+'-'+$('#birth_month').val()+'-'+$('#birth_day').val());

				this.appendChild(input);
			}

			$('#main_form input[type="submit"]').prop('disabled','disabled').addClass('disabled');
		
		});

	}
}