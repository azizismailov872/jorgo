var menu_category = '';
var menu_name = '';
var menu_url = '';
var publish = '';
var status = '';
var order = '';
var html = '';
var id_type = '';

$(document).ready(function()
{
	$('#add-menu-category-form').on('beforeSubmit', function()
	{
		url = 'create-menu-category';
		data = $(this).serialize();
		alert_id = 'add-menu-category-container';
		result_id = '';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$("body").on("click","#edit-menu-category", function()
	{
		item = $("#menu-category option:selected").val();
		data = {item:item};
		url = 'edit-menu-category';
		alert_id = 'edit-menu-category-form';
		result_id = 'edit-menu-category-form-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
        
		return false;
	});
	
	$("body").on("click","#update-menu-category", function()
	{	
		id = $("#update-menu-category-id").val();
		menu_category = $("#update-menu-category-name").val();
		data = {id:id, name:menu_category};
		url = 'update-menu-category';
		alert_id = 'edit-menu-category-container';
		result_id = 'edit-menu-category-form-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$("body").on("click", "#delete-menu-category", function(event)
	{
		id = $("#menu-category option:selected").val();
		data = {id:id};
		url = 'delete-menu-category';
		alert_id = 'edit-menu-category-container';
		result_id = 'edit-menu-category-form-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false
	});
	
	$('#add-menu-form').on('beforeSubmit', function()
	{
		url = 'create-menu';
		data = $(this).serialize();
		alert_id = 'add-menu-container';
		result_id = '';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$("body").on("change", "#menu-type", function(event)
	{	
		id = $("#menu-type option:selected").val();
		data = {id:id};
		url = 'get-menu-category-list';
		alert_id = 'add-menu-container';
		result_id = 'menu-category-wrapper';
		alert(id);
		make_action(url, data, alert_id, result_id, true);
		
		return false
	});
	
	$("body").on("click","#edit-menu", function()
	{
		item = $("#menu-list option:selected").val();
		data = {item:item};
		url = 'edit-menu';
		alert_id = 'edit-menu-form';
		result_id = 'edit-menu-form-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
        
		return false;
	});
	
	$("body").on("click","#update-menu", function()
	{	
		id = $("#update-menu-id").val();
		menu_name = $("#update-menu-name").val();
		menu_url = $("#update-menu-url").val();
		data = {id:id, name:menu_name, url:menu_url};
		url = 'update-menu';
		alert_id = 'edit-menu-container';
		result_id = 'edit-menu-form-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$("body").on("click", "#delete-menu", function(event)
	{
		id = $("#menu-list option:selected").val();
		data = {id:id};
		url = 'delete-menu';
		alert_id = 'edit-menu-container';
		result_id = 'edit-menu-form-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false
	});
	
	$("body").on("change", "#menu-list", function(event)
	{
		id = $("#menu-list option:selected").val();
		data = {id:id};
		url = 'change-menu-publish';
		alert_id = 'edit-menu-container';
		result_id = 'edit-menu-form-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false
	});
	
	$("body").on("click", "#publish-menu", function(event)
	{
		id = $("#menu-list option:selected").val();
		publish = $('#publish-menu').attr('publish');
		data = {id:id, publish:publish};
		url = 'publish-menu';
		alert_id = 'edit-menu-container';
		result_id = 'edit-menu-form-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false
	});
	
	$('#create-content-form').on('beforeSubmit', function()
	{
		data = $(this).serialize();
		url = 'create-content';
		alert_id = 'create-content-container';
		result_id = 'index-wrapper';
		
		reset_validation_fields();
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$("body").on("click", ".status-order", function(event)
	{
		id = $(this).attr('id');
		data = {id:id};
		url = 'menu-status';
		alert_id = 'container';
		result_id = 'datatable';
										
		make_action(url, data, alert_id, result_id, true);
	});
	
	$("body").on("click", ".order-move", function(event)
	{
		id = $(this).attr('id');
		order = $(this).attr('order');
		data = {id:id, order:order};
		url = 'order-up';
		alert_id = 'container';
		result_id = 'datatable';
										
		make_action(url, data, alert_id, result_id, true);
	});
	
	$(".no-wysywig").on("click",function()
	{	
		if($(".form-wysywig").css('display') == 'none')
		{
			$(".form-wysywig").css('display','block');
			$(".form-no-wysywig").css('display','none');
			$(".content-no-wysywig-on").val('0');
		}
		else
		{
			$(".form-wysywig").css('display','none');
			$(".form-no-wysywig").css('display','block');
			$(".content-no-wysywig-on").val('1');
		}
	});
	
	$("body").on("click", ".file_delete", function(event)
	{
		var url = $(this).closest('#files-upload').attr('url');
		id = $(this).closest('#files-upload').attr('item_id');
		var wrap_id = $(this).closest('#files-upload').attr('wrap_id');
		var category = $(this).closest('#files-upload').attr('category');
		var file = $(this).closest('.file_wrap').attr('file');
		var file_type = $(this).closest('.file_wrap').attr('file_type');
		
		data = {url:url, id:id, wrap_id:wrap_id, category:category, file:file, file_type:file_type};
		url = 'delete-file';
		alert_id = 'update-item';
		result_id = '';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false
	});
});

function set_menu_status(id)
{	
	data = {id:id};
	url = 'menu-status';
	alert_id = 'container';
	result_id = 'datatable';
												
	make_action(url, data, alert_id, result_id, true);
}

function set_status(id)
{	
	data = {id:id};
	url = 'content-status';
	alert_id = 'container';
	result_id = 'datatable';
												
	make_action(url, data, alert_id, result_id, true);
}

function set_static_status(id)
{	
	data = {id:id};
	url = 'static-status';
	alert_id = 'container';
	result_id = 'datatable';
												
	make_action(url, data, alert_id, result_id, true);
}

/*function set_success_result(res, url, result_id, alert_id, msg)
{	
	if(url == 'delete-static')
	{	
		if(res.result != '' && res.result != 'false')
		{	
			show_alert_message(alert_id, msg, 'success');
			$('#' + result_id + ' tr[data-key=' + res.id + ']').remove();
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'get-menu-category-list')
	{	
		if(res.html != '')
		{	
			$('#' + result_id).html(res.html);
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'create-menu-category')
	{	
		if(res.result != '' && res.result != 'false' && res.id > 0 && res.name != '')
		{	
			show_alert_message(alert_id, msg, 'success');
				
			$('#menu-category').append($('<option>', 
			{
				value: res.id,
				text: res.name
			}));
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'edit-menu-category')
	{	
		if(res.html != '')
		{	
			$('#' + result_id).html(res.html);
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'update-menu-category' )
	{	
		if(res.result != '' && res.result != 'false' && res.html != '')
		{	
			$('#' + result_id).html(res.html);
		}
		else
		{	
			show_validation_message(alert_id, res.errors);
		}
	}
	else if(url == 'delete-menu-category')
	{	
		if(res.result != '' && res.result != 'false')
		{	
			show_alert_message(alert_id, msg, 'success');
				
			$("#menu-category option[value='" + id + "']").remove();
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'create-menu')
	{	
		if(res.result != '' && res.result != 'false' && res.id > 0 && res.name != '')
		{	
			show_alert_message(alert_id, msg, 'success');
				
			$('#menu').append($('<option>', 
			{
				value: res.id,
				text: res.name
			}));
			
			$('#menu-list').append($('<option>', 
			{
				value: res.id,
				text: res.name
			}));
			
			$('#menu-content-list').append($('<option>', 
			{
				value: res.id,
				text: res.name
			}));
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'edit-menu')
	{	
		if(res.html != '')
		{	
			$('#' + result_id).html(res.html);
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'update-menu' )
	{	
		if(res.result != '' && res.result != 'false' && res.html != '')
		{	
			$('#' + result_id).html(res.html);
		}
		else
		{	
			show_validation_message(alert_id, res.errors);
		}
	}
	else if(url == 'delete-menu')
	{	
		if(res.result != '' && res.result != 'false')
		{	
			show_alert_message(alert_id, msg, 'success');
				
			$("#menu-list option[value='" + id + "']").remove();
			$("#menu-content-list option[value='" + id + "']").remove();
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'menu-delete')
	{	
		if(res.result != '' && res.result != 'false')
		{	
			show_alert_message(alert_id, msg, 'success');
			$('#' + result_id + ' tr[data-key=' + res.id + ']').remove();
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'change-menu-publish' || url == 'publish-menu')
	{	
		if(res.result != '' && res.result != 'false')
		{	
			$('#publish-menu').text(res.publish_msg);
			
			publish = (res.publish > 0) ? 0 : 1;
			$('#publish-menu').attr('publish', publish);
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'create-content')
	{	
		if(res.result != '' && res.result != 'false')
		{
			window.location.replace(res.url);
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'delete-content')
	{	
		if(res.result != '' && res.result != 'false')
		{	
			show_alert_message(alert_id, msg, 'success');
			$('#' + result_id + ' tr[data-key=' + res.id + ']').remove();
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'content-status' || url == 'static-status' || url == 'menu-status')
	{	
		if(res.result != '' && res.result != 'false')
		{				
			if(typeof res.status !== 'undefined')
			{	
				$('td [item_id="' + res.id + '"]').html('<span class="glyphicon glyphicon-' + ((res.status > 0) ? 'download' : 'upload') + '"></span>');
			}
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'delete-file')
	{	
		if(res.result != '' && res.result != 'false' && res.wrap_id > 0 && res.file_type != '' && res.file != '')
		{	
			$('#files-upload[wrap_id="' + res.wrap_id + '"] .file_wrap[file_type="' + res.file_type + '"][file="' + res.file + '"]').remove();
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}	
}*/
