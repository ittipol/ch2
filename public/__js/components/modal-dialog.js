class ModelDialog {

	constructor() {
		this.elem;
		this.action;
	}

	load() {
		this.bind();
	}

	bind() {

		let _this = this;

		$(document).on('click','[data-modal="1"]',function(e){

			e.preventDefault();

			_this.elem = this;
			_this.action = $(this).data('modal-action');
			_this.title(_this.action);

			$('.modal-box').addClass('opened');
			$('.content-wrapper-overlay').addClass('isvisible');
			$('body').css('overflow-y','hidden');

		});

		$('#modal_dialog_cancel').on('click',function(){

			_this.action = null;
			_this.elem = null;

			$('.modal-box').removeClass('opened');
			$('.content-wrapper-overlay').removeClass('isvisible');
			$('body').css('overflow-y','auto');
		});

		$('#modal_dialog_agree').on('click',function(){
			_this.actionType(_this.action,_this.elem);
		});

	}

	title(action) {

		switch(action) {

			case 'delete':
				$('#modal_dialog_title').text('ต้องการลบใช่หรือไม่');
			break;

			case 'submit':

			break;

		}

	}

	actionType(action,elem) {

		switch(action) {

			case 'delete':
				window.location.href = elem.href;
			break;

			case 'submit':

			break;

		}

	}

}