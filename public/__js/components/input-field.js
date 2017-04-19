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

			if(this.value == '') {
				_this.oldInput = '';
				return false;
			}

			const regex = /^[0-9]+\.?[0-9]*$/;

			if(this.value.match(regex) == null) {
				this.value = _this.oldInput;
			  return false;
			}

			_this.oldInput = $(this).val();
			
		});

		$('input[role="number"]').on('focus',function(){
			_this.oldInput = '';
		});

		$('input[role="currency"]').on('keyup',function(){

			if(this.value == '') {
				_this.oldInput = '';
				return false;
			}

			const regex = /^[0-9]+\.?[0-9]{0,2}$/;

			if(this.value.match(regex) == null) {
				this.value = _this.oldInput;
			  return false;
			}

			_this.oldInput = $(this).val();

		});

		$('input[role="currency"]').on('focus',function(){
			_this.oldInput = '';
		});

		$(document).on('click','a[role="button"]',function(e){
			document.onselectstart = function() { return false; };
			event.target.ondragstart = function() { return false; };
		});

		$('label.choice-box input[type="checkbox"]').on('click',function(){
			document.onselectstart = function() { return false; };
			event.target.ondragstart = function() { return false; };
		});

		$('label.choice-box input[type="radio"]').on('click',function(){
			document.onselectstart = function() { return false; };
			event.target.ondragstart = function() { return false; };
		});

	}

}