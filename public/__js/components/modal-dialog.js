class ModelDialog {

	constructor() {

		if(!ModelDialog.instance){
		  this.elem;
		  this.allowed = false;
		  this.waiting = false;
		  ModelDialog.instance = this;
		}

		return ModelDialog.instance;
	}

	load() {
		this.bind();
	}

	bind() {

		let _this = this;

		$(document).on('click','[data-modal="1"]',function(e){

			if(_this.waiting) {
				return;
			}

			e.preventDefault();

			// _this.allowed = true;

			_this.elem = this;
			_this.setTitle($(this).data('modal-title'));

			$('.modal-box').addClass('opened');
			$('.content-wrapper-overlay').addClass('isvisible');
			$('body').css('overflow-y','hidden');

		});

		$('#modal_dialog_cancel').on('click',function(){

			if(_this.waiting) {
				return;
			}

			_this.close();
		});

		$('#modal_dialog_agree').on('click',function(){
			_this.actionType(_this.elem);
		});

	}

	setTitle(title) {
		$('#modal_dialog_title').text(title);
	}

	actionType(elem) {

		if(this.waiting) {
			return;
		}

		this.waiting = true;

		// elem.setAttribute('data-modal','0');

		if(typeof $(elem).attr('href') !== typeof undefined && $(elem).attr('href') !== false) {
		  window.location.href = elem.href;
		}else if($(elem).attr('type') == 'submit') {
			$(elem).trigger('click');
		}else if($(elem).data('trigger')) {

			let trigger = $(elem).data('trigger');
			trigger = trigger.split('|');

			let _elem = document.createElement('div');
			_elem.setAttribute('id','delete_user_review_btn');
			_elem.style.display = 'none';

			$('body').append(_elem);

			$(_elem).trigger('click').remove();

			setTimeout(this.close(),300);
		}

	}

	close() {
		this.action = null;
		this.elem = null;
		this.waiting = false;

		$('.modal-box').removeClass('opened');
		$('.content-wrapper-overlay').removeClass('isvisible');
		$('body').css('overflow-y','auto');
	}

}