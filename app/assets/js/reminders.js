
$(document).ready(function(){
	$('#sandbox-container .input-append.date').datepicker({
	    format: "dd MM yyyy",
	    todayBtn: "linked",
	    keyboardNavigation: false,
	    forceParse: false,
	    autoclose: true,
	    todayHighlight: true
	}).on('changeDate', function(ev){
		load_reminderreadings(format_date_for_api_from_datepicker(ev.date));
	});
	$('#sandbox-container .input-append.date').datepicker("setValue", new Date());

	
	$('#reminder_start_date_parent .input-append.date').datepicker({
	    format: "dd MM yyyy",
	    todayBtn: "linked",
	    keyboardNavigation: false,
	    forceParse: false,
	    autoclose: true,
	    todayHighlight: true
	});

	$('#reminder_start_date_parent .input-append.date').datepicker("setValue", new Date());

	$('#reminder_end_date_parent .input-append.date').datepicker({
	    format: "dd MM yyyy",
	    todayBtn: "linked",
	    keyboardNavigation: false,
	    forceParse: false,
	    autoclose: true,
	    todayHighlight: true
	});


	$('#reminder-form select[name=reminder_type]').change(function(){
		load_reminder_form_by_type();
	});
	$('#reminder-form select[name=repeat_mode]').change(function(){
		var repeat_mode = $('#reminder-form select[name=repeat_mode] option:selected').val();
		if(repeat_mode == 0){
			$("#reminder_end_date_parent").parents('.form-group').addClass("hidden");
			$("#reminder_end_date").val("");
		} else {
			
			$("#reminder_end_date_parent").parents('.form-group').removeClass("hidden");
		}
	});
	$('#reminder-form .btn-save').on('click',function(e){
		event.preventDefault();
		save_reminder(e);
	});

	$('#reminder-modal').on('hidden.bs.modal', function () {
	  reset_reminder_form();
	  
	});

	load_reminderreadings();
});

function load_reminder_form(id,reading_date){
	$("#reminder-modal").modal();
	_DB.Reminder.retrieve(id,function(json,success){
		if(success){
			var reminder = json;
			var form = $('#reminder-form');
			$(form).find('select[name=reminder_type]').val(reminder.type);
			$(form).find('select[name=reminder_type]').attr("disabled",true);
			load_reminder_form_by_type();
			$(form).find('input:text[name=name]').val(reminder.name);
			$(form).find('input:text[name=details]').val(reminder.details);
			$(form).find('.btn-save').attr("data_id",reminder.id);
			$(form).find('.btn-save').attr("data_reading_date",reading_date);
			$(form).find('input:text[name=start_date]').val(reminder.start_date);
			$(form).find('input:text[name=end_date]').val(reminder.end_date);
			if(reminder.type == 2){
				$(form).find('input:text[name=morning_count]').val(reminder.morning_count);
				$(form).find('input:text[name=afternoon_count]').val(reminder.afternoon_count);
				$(form).find('input:text[name=night_count]').val(reminder.night_count);	
			}
			
			$(form).find('select[name=repeat_mode]').val(reminder.repeat_mode);
		}
	});
}
function save_reminder(event){
	event.preventDefault();
	var form = $('#reminder-form');
	form.validate();
	if(form.valid()){
		var reminder = {};
		reminder.type =  $(form).find('select[name=reminder_type] option:selected').val();
		reminder.name =  $(form).find('input:text[name=name]').val();
		reminder.details =  $(form).find('input:text[name=details]').val();
		reminder.repeat_mode = $(form).find('select[name=repeat_mode] option:selected').val();
		if(reminder.type == 2 ){
			reminder.morning_count = $(form).find('input:text[name=morning_count]').val();
			reminder.afternoon_count = $(form).find('input:text[name=afternoon_count]').val();
			reminder.night_count = $(form).find('input:text[name=night_count]').val();
		}

		reminder.start_date = format_date_for_api_from_datepicker($(form).find("#reminder_start_date").val())
		if($(form).find("#reminder_end_date").val())
			reminder.end_date = format_date_for_api_from_datepicker($(form).find("#reminder_end_date").val())
		var id = $(form).find('.btn-save').attr("data_id");
		if(!id){
			_DB.Reminder.create(reminder,function(json,success){
				post_reminder_save(json,success);
			});
		} else {
			_DB.Reminder.update(id,reminder,function(json,success){
				post_reminder_save(json,success);
			});
		}
	}
}
function post_reminder_save(json,success){
	if(success){
		load_reminderreadings(format_date_for_api_from_datepicker($("#reminder_date").val()));
	}
	reset_reminder_form();
}
function reset_reminder_form(){
	$("#reminder-modal").modal('hide');
	var form = $('#reminder-form');
	$(form).find('select[name=reminder_type]').val('');
	load_reminder_form_by_type();
	$(form).find('input:text[name=name]').val('');
	$(form).find('input:text[name=details]').val('');
	$(form).find('.btn-save').attr("data_id",'');
	$(form).find('.btn-save').attr("data_reading_date",'');
	//$(form).find('input:text[name=start_date]').val('');
	$(form).find('input:text[name=end_date]').val('');
	$(form).find('select[name=repeat_mode]').val('');
	$(form).find('input:text[name=morning_count]').val('');
	$(form).find('input:text[name=afternoon_count]').val('');
	$(form).find('input:text[name=night_count]').val('');
	$(form).find('select[name=reminder_type]').removeAttr("disabled");
}
function load_reminder_form_by_type(){
	var form = $('#reminder-form');
	var type =  $(form).find('select[name=reminder_type] option:selected').val();
	if( type != 0 ){
		//$(form).find('input:text[name=name]').parents('.form-group').removeClass('hidden');
		//$(form).find('input:text[name=details]').parents('.form-group').removeClass('hidden');
		//$(form).find('input:text[name=start_date]').parents('.form-group').removeClass('hidden');
		//$(form).find('input:text[name=end_date]').parents('.form-group').removeClass('hidden');
		//$(form).find('button[name=submit]').parents('.form-group').removeClass('hidden');	
	} else {
		//$(form).find('input:text[name=name]').parents('.form-group').addClass('hidden');
		//$(form).find('input:text[name=details]').parents('.form-group').addClass('hidden');	
		//$(form).find('input:text[name=start_date]').parents('.form-group').addClass('hidden');
		//$(form).find('input:text[name=end_date]').parents('.form-group').addClass('hidden');
		//$(form).find('button[name=submit]').parents('.form-group').addClass('hidden');	
	}
	
	if(type == 2){
		$(form).find('input:text[name=morning_count]').parents('.form-group').removeClass('hidden');
		$(form).find('input:text[name=afternoon_count]').parents('.form-group').removeClass('hidden');
		$(form).find('input:text[name=night_count]').parents('.form-group').removeClass('hidden');
	} else if( type == 1 || type == 3 || type == 4 ){
		$(form).find('input:text[name=morning_count]').parents('.form-group').addClass('hidden');
		$(form).find('input:text[name=afternoon_count]').parents('.form-group').addClass('hidden');
		$(form).find('input:text[name=night_count]').parents('.form-group').addClass('hidden');
		$(form).find('input:text[name=morning_count]').val('');
		$(form).find('input:text[name=afternoon_count]').val('');
		$(form).find('input:text[name=night_count]').val('');
	}
}

function set_is_complete(rr,_t_elem){
	var today = new Date();
	today.setHours(0,0,0,0);
	var rr_date = new Date(Date.parse(rr.reading_date));
	rr_date.setHours(0,0,0,0);
	var _i_c = $(_t_elem).find('.is_complete button');
	if(rr.complete_check) {
		set_medicine_taken(_i_c);
	}
	else{
		if(rr_date - today < 0)
			set_medicine_expired(_i_c);
		else if (rr_date - today ==0)
			set_medicine_pending(_i_c);
		else
			set_medicine_future(_i_c);
		
	}
}

function set_rr_event_count(rr,_t_elem){
	var today = new Date();
	today.setHours(0,0,0,0);
	var rr_date = new Date(Date.parse(rr.reading_date));
	rr_date.setHours(0,0,0,0);
	var _m_c = $(_t_elem).find('.morning_check button');
	var _a_c = $(_t_elem).find('.afternoon_check button');
	var _n_c = $(_t_elem).find('.night_check button');
	if(!rr.reminder.morning_count)
		set_medicine_unset(_m_c,rr.reminder.morning_count);
	else if(rr.morning_check) {
		set_medicine_taken(_m_c,rr.reminder.morning_count);
	}
	else{
		if(rr_date - today < 0)
			set_medicine_expired(_m_c,rr.reminder.morning_count);
		else if (rr_date - today ==0)
			set_medicine_pending(_m_c,rr.reminder.morning_count);
		else
			set_medicine_future(_m_c,rr.reminder.morning_count);
	}
	if(!rr.reminder.afternoon_count)
		set_medicine_unset(_a_c,rr.reminder.afternoon_count);
	else if(rr.afternoon_check) {
		set_medicine_taken(_a_c,rr.reminder.afternoon_count);
	}
	else{
		if(rr_date - today < 0)
			set_medicine_expired(_a_c,rr.reminder.afternoon_count);
		else if (rr_date - today ==0)
			set_medicine_pending(_a_c,rr.reminder.afternoon_count);
		else
			set_medicine_future(_a_c,rr.reminder.afternoon_count);
	}
	
	if(!rr.reminder.night_count)
		set_medicine_unset(_n_c,rr.reminder.night_count);
	else if(rr.night_check) {
		set_medicine_taken(_n_c,rr.reminder.night_count);
	}
	else{
		if(rr_date - today < 0)
			set_medicine_expired(_n_c,rr.reminder.night_count);
		else if (rr_date - today ==0)
			set_medicine_pending(_n_c,rr.reminder.night_count);
		else
			set_medicine_future(_n_c,rr.reminder.night_count);
	}
}

function toggle_is_completed(elem,readingdate){
	if($(elem).hasClass("disabled")){
		return;
	} else {
		if($(elem).hasClass("btn-warning")){
			$(elem).addClass("disabled");
			var id = $(elem).parents('.rr_row').attr('data_id');
			rr = {};
			rr.complete_check = 'True';
			rr.reading_date = readingdate;
			_DB.ReminderReading.update(id,rr,function(json,success){
				if(success){
					$(elem).removeClass("btn-warning");
					set_medicine_taken(elem);
				} else {
					$(elem).removeClass("disabled");
				}
				
			});
		}
	}
}
function toggle_medicine_check(elem,readingdate){
	if($(elem).hasClass("disabled")){
		return;
	} else {
		if($(elem).hasClass("btn-warning")){
			$(elem).addClass("disabled");
			var id = $(elem).parents('.rr_row').attr('data_id');
			rr = {};
			rr[$(elem).attr('check_type')] = 'True';
			rr.reading_date = readingdate;
			_DB.ReminderReading.update(id,rr,function(json,success){
				if(success){
					$(elem).removeClass("btn-warning");
					set_medicine_taken(elem);
				} else {
					$(elem).removeClass("disabled");
				}
				
			});
		}
	}
}

function set_medicine_unset(elem,label){
	if(typeof label === "undefined") var label = ' &nbsp; ';
	$(elem).html(label).addClass('disabled');
}
function set_medicine_taken(elem,label){
	if(typeof label === "undefined") var label = '&#10004;';
	$(elem).html(label).addClass('btn-success').addClass('disabled');
}
function set_medicine_pending(elem,label){
	if(typeof label === "undefined") var label = '&#33;';
	$(elem).html(label).addClass('btn-warning');
}
function set_medicine_expired(elem,label){
	if(typeof label === "undefined") var label = '&otimes;';
	$(elem).html(label).addClass('btn-danger').addClass('disabled');
}
function set_medicine_future(elem,label){
	if(typeof label === "undefined") var label = '&#33;';
	$(elem).html(label).addClass('btn-warning').addClass('disabled');
}
function load_reminderreadings(rr_date){
	$("#rr_messages").show();
	if(!rr_date) {
		rr_date = format_date_for_api();
	}
	var options = {}
	options.reading_date =  rr_date;
	_DB.ReminderReading.list(options,function(json,success){
		$("#rr_messages").hide();
		if(success){
			var predessor = $("#rr_container");
			$(predessor).html('');
			if(json.count){
				var rr_data = json.results;
				
				$.each(rr_data,function(i,val){
					
					var _t = get_rr_row_template(val.reminder.type);
					if(!_t) return false;

					var _t_elem = $.parseHTML(_t);
					
					$(_t_elem).attr("data_id",val.id);
					
					$(_t_elem).find('.name').html(val.reminder.name);
					$(_t_elem).find('.details').html(val.reminder.details);
					if(val.reminder.type == '4' || val.reminder.type == '1' || val.reminder.type == '3'){
						set_is_complete(val,_t_elem);
					} else {
						set_rr_event_count(val,_t_elem);
					}
					$($(_t_elem).find(".is_complete button")[0]).attr('onclick','').unbind('click');
					$(_t_elem).find('.is_complete button').on('click',function(){
						toggle_is_completed(this,rr_date);
					});
					$($(_t_elem).find(".medicine_check button")[0]).attr('onclick','').unbind('click');
					$(_t_elem).find('.medicine_check button').on('click',function(){
						toggle_medicine_check(this,rr_date);
					});
					$($(_t_elem).find(".edit-reminder button")[0]).attr('onclick','').unbind('click');
					$(_t_elem).find('.edit-reminder').on('click',function(){
						load_reminder_form(val.reminder.id,rr_date);
					});
					$(predessor).append(_t_elem);
					
				});
			}
		}
	});
}