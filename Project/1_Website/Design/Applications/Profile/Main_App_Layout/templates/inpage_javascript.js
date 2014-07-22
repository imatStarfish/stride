$(document).ready(function() {
	
	$.fn.serializeObject = function()
	{
	   var o = {};
	   var a = this.serializeArray();
	   $.each(a, function() {
	       if (o[this.name]) {
	           if (!o[this.name].push) {
	               o[this.name] = [o[this.name]];
	           }
	           o[this.name].push(this.value || '');
	       } else {
	           o[this.name] = this.value || '';
	       }
	   });
	   return o;
	};

	

	$(document).on("click", ".next-btn", function(){
		var part_number = parseInt($(this).attr("data-part_number")) + 1;
		$('#side_nav_tab a[href="#part'+part_number+'"]').tab("show");
	
		saveData(this);
	})
	
	$(document).on("click", ".prev-btn", function(){
		var part_number = parseInt($(this).attr("data-part_number")) - 1;
		$('#side_nav_tab a[href="#part'+part_number+'"]').tab("show");
	
		saveData(this);
	})
	
	
	function saveData(selector)
	{
		part_number = $(selector).attr("data-part_number");
		inputs 		= $('#part'+ part_number).find("select, textarea, input").serializeObject();
		inputs.part_number = part_number;
		
		console.log(inputs);
		
		$.php("/ajax/profile/account/save", inputs);
	}
	
});




$(window).load(function () {


});