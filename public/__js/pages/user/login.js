var Login = {

}

Login.load = function() {
  // init
  Login.validation();
}

Login.validation = function() {
	// Setup form validation on the #register-form element
  $("#login_form").validate({
  
      // Specify the validation rules
      rules: {
    		email: {
            required: true,
        },
    		password: "required",
      },
      
      // Specify the validation error messages
      messages: {
    		email: {
          required: "",
        },
    		password: "",
      },

      errorClass: "error",
      
      submitHandler: function(form) {
        form.submit();
      }
  });

}

$(document).ready(function(){
	Login.load(); 
});