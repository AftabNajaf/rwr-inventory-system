(function($) {
	'use strict';
	$.datetimepicker.setLocale('en');

	$('#datetimepicker_format').datetimepicker({value:'2015-04-15 05:03', format: $("#datetimepicker_format_value").val()});
	$("#datetimepicker_format_change").on("click", function(e){
		$("#datetimepicker_format").data('xdsoft_datetimepicker').setOptions({format: $("#datetimepicker_format_value").val()});
	});
	$("#datetimepicker_format_locale").on("change", function(e){
		$.datetimepicker.setLocale($(e.currentTarget).val());
	});
	$('.datetimepicker_format').datetimepicker({value:'2015-04-15 05:03', format: $(".datetimepicker_format_value").val()});
	$('.default_datetimepicker').datetimepicker({
		//value:'2015-05-15 05:30',										
		format:'Y-m-d h:i',
		formatDate:'Y-m-d h:i',
		timepicker:true,
		timepickerScrollbar:true
	});
	$('.default_dtpp').datetimepicker({
		//value:'2015-05-15',										
		format:'Y-m-d',
		formatDate:'Y-m-d',
		minDate: 0,
		timepicker:false,
		timepickerScrollbar:false
	});
	
	$('.default_dtp').datetimepicker({
		//value:'2015-05-15',										
		format:'m-d-Y',
		formatDate:'m-d-Y',
		timepicker:false,
		timepickerScrollbar:false,
		maxDate: new Date 
	});
	
	//$("#datepicker").datepicker({ maxDate: new Date, minDate: new Date(2007, 6, 12) });
	$('.timepicker').datetimepicker({
		datepicker:false,
		format:'H:i',
		step:5
	});

	$('.dateMount_format').datetimepicker({
		format:'Y-m-d',
		formatDate:'Y-m-d',
		value:'',
		timepicker:false,
		timepickerScrollbar:false
	});
	
	$('.dateMount_tomorrow').datetimepicker({
		format:'d M',
		formatDate:'Y/m/d',
		value:'+2017/01/02',
		timepicker:false,
		timepickerScrollbar:false,
	});

})(jQuery);

/**/