class InputField {

	constructor() {
		this.oldInput = '';
	}

	load() {
		this.bind();
	}

	bind() {

		let _this = this;
		$('input[role="number"]').on('keyup',function(){

			if($(this).val() == '') {
				_this.oldInput = '';
				return false;
			}

			const regex = /^(\d+|\d+.\d+)$/g;

			if($(this).val().match(regex) == null) {
			  $(this).val(_this.oldInput);
			  return false;
			}

			_this.oldInput = $(this).val();
			
		});

		$(document).on('click','a[role="button"]',function(e){
			document.onselectstart = function() { return false; };
			event.target.ondragstart = function() { return false; };
		});

	}

}