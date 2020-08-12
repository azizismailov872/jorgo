function set_success_result(res, url, result_id, alert_id, msg)
{	
	if(url == 'status')
	{	
		if(res.result != '' && res.result != 'false')
		{	
			$('ul [item_id="' + res.id + '"]').html('<a id="item-publish" href="#" item-id="' + res.id + '" type="' + res.type +'" item-status="' + res.status + '"><i class="fa fa-arrow-circle-' + ((res.status > 0) ? 'down' : 'up') + '"></i></a>');
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
	
	else if(url == 'delete-static')
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
				
			$('#category-menu-name').val('');
			$('#menu-category').append($('<option>', 
			{
				value: res.id,
				text: res.name
			}));
			
			$('#menu-category-id').append($('<option>', 
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
		if(res.result != '' && res.result != 'false' && res.html != '' && res.id > 0 && res.name != '')
		{	
			$('#' + result_id).html(res.html);
			$("#menu-category-id option[value='" + res.id + "']").text(res.name);
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
			$("#menu-category-id option[value='" + id + "']").remove();
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
			$('#menu-name').val('');
			$('#menu-url').val('');
				
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
		if(res.result != '' && res.result != 'false' && res.html != '' && res.id > 0 && res.name != '')
		{	
			$("#menu-content-list option[value='" + res.id + "']").text(res.name);
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
	else if(url == 'news/delete')
	{	
		if(res.result != '' && res.result != 'false')
		{	
			$('#' + result_id + ' tr[data-key=' + res.id + ']').remove();
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'news/status')
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
	else if(url == 'create-group')
	{	
		if(res.result != '' && res.result != 'false' && res.id > 0 && res.name != '')
		{	
			$('#create-user').val('');
			show_alert_message(alert_id, msg, 'success');
				
			$('#users-groups').append($('<option>', 
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
	else if(url == 'edit-group')
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
	else if(url == 'update-group' )
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
	else if(url == 'delete-group')
	{	
		if(res.result != '' && res.result != 'false')
		{	
			show_alert_message(alert_id, msg, 'success');
				
			$("#users-groups option[value='" + id + "']").remove();
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'add-user')
	{	
		if(res.result != '' && res.result != 'false')
		{
			window.location.replace(res.url);
		}
		else
		{	
			show_validation_message(alert_id, res.errors);
		}
	}
	else if(url == 'users/delete-user')
	{
		if(res.result != '' && res.result != 'false')
		{	
			$('#' + result_id + ' tr[data-key=' + res.id + ']').remove();
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'get-inherit-roles' || url == 'check-inherit-role')
	{	
		if(res.html != '')
		{	
			$('#' + result_id).html(res.html);
						
			if(url == 'inherit-role') 
			{	
				window.history.pushState("Permissions", "Permissions", "");
			}
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'inherit-role')
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
	else if(url == 'delete-inherit-roles')
	{
		if(res.result != '' && res.result != 'false' && res.html != '')
		{
			show_alert_message(alert_id, msg, 'success');
					
			$('#' + result_id).html(res.html);
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'create-role' || url == 'create-module')
	{
		if(res.result != '' && res.result != 'false')
		{			
			show_alert_message(alert_id, msg, 'success');
					
			if(url == 'create-role')
			{	
				if(res.role != '')
				{	
					$('#create-role-form')[0].reset();
							
					$('#delete-role-list').append($('<option>', 
					{
						value: res.role,
						text: res.role
					}));
							
					$('#delete-inherit-role-id').append($('<option>', 
					{
						value: res.role,
						text: res.role
					}));
									
					$('#inherit-role-id').append($('<option>', 
					{
						value: res.role,
						text: res.role
					}));
							
					$('#inherit-roles').append($('<option>', 
					{
						value: res.role,
						text: res.role
					}));
							
					$('#delete-inherit-roles').append($('<option>', 
					{
						value: res.role,
						text: res.role
					}));
				}
			}
			else if (url == 'create-module')
			{	
				if(res.result != '' && res.result != 'false')
				{	
					$('#create-module').val('');
					show_alert_message(alert_id, msg, 'success');
							
					if(res.id > 0 && res.name != '')
					{	
						$('#delete-' + result_id + '-list').append($('<option>', 
						{
							value: res.id,
							text: res.name
						}));
												
						$('#' + result_id + '-list').append($('<option>', 
						{
							value: res.id,
							text: res.name
						}));
					}
				}
			}
		}
		else
		{	
			show_validation_message(alert_id, res.errors);
		}	
	}
	else if (url == 'delete-role' || url == 'delete-module')
	{
		if(res.result != '' && res.result != 'false')
		{	
			show_alert_message(alert_id, msg, 'success');
							
			if(url == 'delete-role')
			{
				$("#delete-role-list option[value='" + item + "']").remove();
				$("#inherit-role-id option[value='" + item + "']").remove();
				$("#inherit-roles option[value='" + item + "']").remove();
				$("#delete-inherit-role-id option[value='" + item + "']").remove();
				$("#delete-inherit-roles option[value='" + item + "']").remove();
			}
			else if(url == 'delete-module')
			{
				$("#delete-module-list option[value='" + item + "']").remove();
				$("#module-list option[value='" + item + "']").remove();
			}
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if (url == 'create-permission')
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
	else if(url == 'delete-permission')
	{
		if(res.result != '' && res.result != 'false')
		{	
			$('#' + result_id + ' tr[key=' + res.id + ']').remove();
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	if(url == 'news/delete')
	{	
		if(res.result != '' && res.result != 'false')
		{	
			$('#' + result_id + ' tr[data-key=' + res.id + ']').remove();
		}
		else
		{	
			show_alert_message(alert_id, msg, 'danger');
		}
	}
	else if(url == 'news/status')
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
}
