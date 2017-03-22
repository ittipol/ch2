class ModelDialog {

	constructor() {
		this.elem;
		this.action;
		this.allowed = false;
		this.waiting = false;
	}

	load() {
		this.bind();
	}

	bind() {

		let _this = this;

		$(document).on('click','[data-modal="1"]',function(e){

			if(_this.waiting) {
				return true;
			}

			e.preventDefault();

			// _this.allowed = true;

			_this.elem = this;
			_this.action = $(this).data('modal-action');
			_this.setTitle(_this.action,$(this).data('modal-title'));

			$('.modal-box').addClass('opened');
			$('.content-wrapper-overlay').addClass('isvisible');
			$('body').css('overflow-y','hidden');

		});

		$('#modal_dialog_cancel').on('click',function(){

			if(_this.waiting) {
				return;
			}

			_this.action = null;
			_this.elem = null;

			// _this.allowed = false;

			$('.modal-box').removeClass('opened');
			$('.content-wrapper-overlay').removeClass('isvisible');
			$('body').css('overflow-y','auto');
		});

		$('#modal_dialog_agree').on('click',function(){
			_this.actionType(_this.action,_this.elem);
		});

	}

	setTitle(action,title) {

		$('#modal_dialog_title').text(title);

		// switch(action) {

		// 	case 'delete':
		// 		$('#modal_dialog_title').text('ต้องการลบใช่หรือไม่');
		// 	break;

		// 	case 'submit':
		// 		$('#modal_dialog_title').text('ต้องการลบใช่หรือไม่');
		// 	break;

		// }

	}

	actionType(action,elem) {

		if(this.waiting) {
			return;
		}

		this.waiting = true;

		// elem.setAttribute('data-modal','0');

		if (typeof $(elem).attr('href') !== typeof undefined && $(elem).attr('href') !== false) {
		  window.location.href = elem.href;
		}else if($(elem).attr('type') == 'submit') {
			$(elem).trigger('click');
		}

	}

}