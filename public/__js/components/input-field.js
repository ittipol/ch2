class InputField {

	constructor() {}

	load() {
		this.bind();
	}

	bind() {

		$('input[role="number"]').on('keydown',function(e){

			if(((e.keyCode < 96) || (e.keyCode > 105)) && ((e.keyCode < 48) || (e.keyCode > 57)) && (e.keyCode != 8) && (e.keyCode != 110) && (e.keyCode != 190)) {
			  e.preventDefault();
			  return false;
			}
			
		});

		$(document).on('click','a[role="button"]',function(e){
			document.onselectstart = function() { return false; };
			event.target.ondragstart = function() { return false; };
		});

	}

}