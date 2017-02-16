$(document).ready(function(){
	$('form').submit(function(){

		let day = $('#birth_day').val();
		let month = $('#birth_month').val();
		let year = $('#birth_year').val();

		let input = document.createElement("input");
		input.setAttribute("type", "hidden");
		input.setAttribute("name", "birth_date");
		input.setAttribute("value", year+'-'+month+'-'+day);

		this.appendChild(input);

	});
});