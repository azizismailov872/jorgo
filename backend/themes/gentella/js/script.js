var data = null;
var single = false;
var id = '';
var count = 0;
var item = '';
var index = '';
var url = '';
var alert_id = '';
var result_id = '';
var container_id = '';
var pathname = '';
var get_data = '';
var msg = '';

$(document).ready(function()
{
	$("body").on("click","#reset-form", function()
	{
		pathname = location.href;
		
		if(pathname.indexOf('?') > -1)
		{
			pathname = pathname.split("?");
			//get_data = pathname[1];
			pathname = pathname[0];
		}
		
		/*if(get_data != '')
		{	
			get_data = get_data.split('&');
			get_data = '?' + get_data[0];
		}*/
		
		pathname = pathname.substr(pathname.lastIndexOf('/') + 1) + get_data;
		window.location.replace(pathname);
        
		return false;
	});
	
	$('#set-pager').on('change', function() 
	{
		count = this.value;
		index = $(this).attr("index");
		url = $(this).attr("url");
		
		window.location.replace('set-page/' + index + '/' + url + '/' + count);
		
		return false;
	});
})

function make_action(url, role, alert_id, result_id, reset_alert)
{	
	msg = 'Failure!';
	
	if(reset_alert)
	{
		reset_alerts('alert-success', 'alert-danger');
	}
	
	$.ajax(
	{
		url: url,
		type: 'POST',
		data: data,
		dataType: 'json',
		success: function(res)
		{	
			msg = res.msg;
			
			set_success_result(res, url, result_id, alert_id, msg);
		},
		error: function()
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	});
}

function reset_validation_fields()
{
	$('body .help-block').css('color', '#73879C');
	$('body .help-block').html('');
	$('body .form-control').css('border', '1px solid #73879C');
	$('body label').css('color', '#73879C');
}

function show_validation_message(alert_id, errors, error_field)
{	
	if((Object.keys(errors).length > 0))		
	{	
		single = (errors[0] > 0) ? false : true;
		form_id = errors[2];
		
		reset_validation_fields();
		
		$.each(errors[1], function (error_field_id, msg) 
		{	
			if(!single)
			{
				error_field_id = error_field_id.split("-");
				error_field_id = error_field_id[1];
			}
			
			error_field_id = (typeof error_field !== 'undefined') ? error_field : error_field_id;
			
			$('#' + form_id + ' #field_' + error_field_id + ' .help-block').css('color', '#a94442');
			$('#' + form_id + ' #field_' + error_field_id + ' label').css('color', '#a94442');
			$('#' + form_id + ' #field_' + error_field_id + ' .form-control').css('border', '1px solid #a94442');
			$('#' + form_id + ' #field_' + error_field_id + ' .help-block').html(msg);
		});
	}
	else
	{	
		show_alert_message(alert_id, msg, 'danger');
	}
}

function show_alert_message(alert_id, msg, alert_type)
{
	$('body .help-block').css('color', '#73879C');
	$('body .form-control').css('border', '1px solid #73879C');
	$('body label').css('color', '#73879C');
	
	$('#' + alert_id + ' #alert-' + alert_type).css('display', 'block');
	$('#' + alert_id + ' #alert-' + alert_type + ' .alert_message').html('<strong>' + msg + '<strong>');
}

function reset_alerts(alert_success_id, alert_danger_id)
{
	$('body #' + alert_success_id).css('display', 'none', '!important');
	$('body #' + alert_danger_id).css('display', 'none', '!important');
}

function set_pager(url, index, count, alert_id, msg_id)
{
	msg = 'Failure!';
	
	$.ajax(
	{
		url: 'set-page',
		type: 'POST',
		data: {index:index, count:count},
		dataType: 'json',
		success: function(res)
		{	
			if(res.result != '' && res.result != 'false' && url != '')
			{	
				window.location.replace(url);
			}
			else
			{	
				$('#' + alert_id + ' #' + msg_id).css('display', 'block');
				$('#' + alert_id + ' #' + msg_id + ' .alert_message').html('<strong>' + msg + '<strong>');
			}
		},
		error: function()
		{	
			$('#' + alert_id + ' #' + msg_id).css('display', 'block');
			$('#' + alert_id + ' #' + msg_id + ' .alert_message').html('<strong>' + msg + '<strong>');
		}
	});
}
