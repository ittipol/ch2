class InputFieldGroupList {

	constructor(panel,inputName) {
		this.panel = panel;
		this.inputName = inputName;
		this.fields = [];
		this.index = 1;
		this.runningNumber = 0;
	}

	load() {
		this.createInput();
		this.bind();
	}

	bind() {

		let _this = this;

		$('#'+this.panel+' .text-add').on('click',function(){
      _this.createInput();
    });

    $(document).on('click','#'+this.panel+' .button-clear-text',function(){
      
      $(this).parent().remove();

      if(--_this.index == 1) {
        _this.createInput();
      }

    });

	}

	setData(data = []) {
		console.log(data);
	}

	wrapper(inputFieldHtml = '') {

		if(inputFieldHtml == '') {
			return false;
		}

		let html = '<div class="text-input-group-wrapper">';
		html += '<p class="error-message"></p>';
		html += inputFieldHtml;
		if(this.index > 1){
		  html += '<span class="button-clear-text" style="visibility: visible;">×</span>';
		}
		html += '</div>';

		return html;

	}

	createInput() {

		let len = this.fields.length;

		let html = '';
		for (var i = 0; i < len; i++) {

			switch(this.fields[i][0]) {

				case 'text':
					html += this.createTextInput(this.fields[i][1],this.fields[i][2]);
				break;

			}

		}

		$('#'+this.panel+' .text-group-panel').append(this.wrapper(html));
		this.runningNumber++;
		this.index++;

	}

	createTextInput(name,placeholder,value = '') {
		return '<input type="text" name="'+this.inputName+'['+this.runningNumber+']['+name+']" placeholder="'+placeholder+'" autocomplete="off" value="'+value+'">';
	}

	setField(fieldType,name,placeholder = '',value = '') {
		this.fields.push([fieldType,name,placeholder,value]);
	}

}