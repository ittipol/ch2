class TextInputStack {

  constructor(panel = '_text_input_panel',textInputName = '_text_input_data',placeholder = '',options = []) {
    this.panel = panel;
    this.textInputName = textInputName;
    this.placeholder = placeholder;
    this.options = options;
    this.index = 1;
    this.runningNumber = 0;
    this.checkEmpty = false;
    this.disableCreating = false;
    this.dataAtLeast = 0;
    this.checkType = null;
    this.regex = {
      tel : /^[0-9+][0-9\-]{3,}[0-9]$/g,
      numeric : /^[0-9]+$/g,
      string : /^\w+$/g
    };
    this.errors = {
      tel: '* หมายเลขโทรศัพท์ไม่ถูกต้อง',
      numeric: '* กรุณากรอกเป็นตัวเลข',
      string: '* กรุณากรอกเป็นตัวหนังสือ'
    }
  }

  load(data = []) {

    if((data.length > 0) && (data != '[]')) {

      for (var i = 0; i < data.length; i++) {

        if(typeof data[i]['name'] != 'undefined') {
          this.createTextInput(data[i]['name'],data[i]['type'],true);
        }else{
          this.createTextInput(data[i],'',true);
        }

      };
    }

    this.createTextInput('','');
    this.bind();
  }

  bind() {

    let _this = this;

    $('#'+this.panel+' .text-add').on('click',function(){
      _this.createTextInput();
    });

    $(document).on('click','#'+this.panel+' .button-clear-text',function(){
      
      $(this).parent().remove();

      if(--_this.index == 1) {
        _this.createTextInput('','',true);
      }

    });

    $('#main_form').on('submit',function(){

      if(_this.checkEmpty) {

        let dataCount = 0;
        let hasError = false;

        $('#'+_this.panel+' input[type="text"]').each(function(index) {

          ++dataCount;

          $(this).removeClass('input-error');
          $(this).parent().find('p.error-message').text('');

          if(this.value == '') {

            --dataCount;
            $(this).addClass('input-error');
            hasError = true;

          }else if(typeof _this.regex[_this.checkType] != 'undefined') {

            let patt = new RegExp(_this.regex[_this.checkType]);

            if(!patt.test(this.value)) {

              --dataCount;
              $(this).addClass('input-error');
              $(this).parent().find('p.error-message').text(_this.errors[_this.checkType]);
              hasError = true;
            
            }

          }
          
        });

        if(hasError || _this.dataAtLeast > dataCount) {
          // hasError = true;
          return false;
        }

        // if(hasError) {
        //   return false;
        // }

      }
      
    });

  }

  createTextInput(value = '',type='',forceCreate = false) {

    if((this.disableCreating && (this.runningNumber > 0)) && !forceCreate) {
      return ;
    }

    let html = '';
    html += '<div class="text-input-wrapper">';

    html += '<p class="error-message"></p>'

    if(this.options.length > 0) {
      html += '<select name="'+this.textInputName+'['+this.runningNumber+'][type]">';
      for (var i = 0; i < this.options.length; i++) {
        if(type == this.options[i][0]) {
          html += '<option value="'+this.options[i][0]+'" selected>'+this.options[i][1]+'</option>';
        }else{
          html += '<option value="'+this.options[i][0]+'">'+this.options[i][1]+'</option>';
        }
      };
       html += '</select>';
    }
   
    html += '<input type="text" name="'+this.textInputName+'['+this.runningNumber+'][value]" placeholder="'+this.placeholder+'" autocomplete="off" value="'+value+'">';
    if(((this.index > 1) || (value != '')) && !this.disableCreating){
      html += '<span class="button-clear-text" style="visibility: visible;">×</span>';
    }
    html += '</div>';

    this.runningNumber++;
    this.index++;
    $('#'+this.panel+' .text-group-panel').append(html);

  }

  dataCheck(type) {
    this.checkType = type;
  }

  setDataInputAtLease(dataAtLeast) {
    this.dataAtLeast = dataAtLeast
  }

  disableCreatingInput() {
    this.disableCreating = true;
  }

  enableCheckingEmpty() {
    this.checkEmpty = true;
  }

}