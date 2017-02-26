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

		for (var i = 0; i < data.length; i++) {

			let html = '';
			for (var j = 0; j < this.fields.length; j++) {
				html += this.getHtml(this.fields[j],data[i][this.fields[j][1]]);
			}

			$('#'+this.panel+' .text-group-panel').append(this.wrapper(html,true));
			this.runningNumber++;
			this.index++;

		}

	}

	createInput() {

		let html = '';
		for (var i = 0; i < this.fields.length; i++) {

			html += this.getHtml(this.fields[i])

		}

		$('#'+this.panel+' .text-group-panel').append(this.wrapper(html));
		this.runningNumber++;
		this.index++;

	}

	getHtml(field,data = '') {

		let html = '';
		switch(field[0]) {

			case 'text':
				html += this.createTextInput(field[1],field[2],data);
			break;

		}

		return html;

	}

	createTextInput(name,placeholder,value = '') {
		return '<input type="text" name="'+this.inputName+'['+this.runningNumber+']['+name+']" placeholder="'+placeholder+'" autocomplete="off" value="'+value+'">';
	}

	wrapper(inputFieldHtml = '',hasValue = false) {

		if(inputFieldHtml == '') {
			return false;
		}

		let html = '<div class="text-input-group-wrapper">';
		html += '<p class="error-message"></p>';
		html += inputFieldHtml;
		if((this.index > 1) || hasValue){
		  html += '<span class="button-clear-text" style="visibility: visible;">×</span>';
		}
		html += '</div>';

		return html;

	}

	setField(fieldType,name,placeholder = '',value = '') {
		this.fields.push([fieldType,name,placeholder,value]);
	}

}