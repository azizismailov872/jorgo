var group_name = '';

$(document).ready(function()
{	
	$('#add-group-form').on('beforeSubmit', function()
	{
		url = 'create-group';
		data = $(this).serialize();
		alert_id = 'add-group-container';
		result_id = '';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$("body").on("click","#edit-group", function()
	{
		item = $("#users-groups option:selected").val();
		data = {item:item};
		url = 'edit-group';
		alert_id = 'edit-group-form';
		result_id = 'edit-group-form-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
        
		return false;
	});
	
	$("body").on("click","#update-group", function()
	{	
		id = $("#update-group-id").val();
		group_name = $("#update-group-name").val();
		data = {id:id, name:group_name};
		url = 'update-group';
		alert_id = 'edit-group-container';
		result_id = 'edit-group-form-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$("body").on("click", "#delete-group", function(event)
	{
		id = $("#users-groups option:selected").val();
		data = {id:id};
		url = 'delete-group';
		alert_id = 'edit-group-container';
		result_id = 'edit-group-form-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false
	});
	
	$('#create-user-form').on('beforeSubmit', function()
	{	
		alert('Нажал');
		data = $(this).serialize() + "&" + $("#edit-group-form").serialize();
		url = 'add-user';
		alert_id = 'create-user-container';
		result_id = 'index-wrapper';
		
		reset_validation_fields();
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
})

function redirect_by_url(url_data)
{	
	if((Object.keys(url_data).length > 0))		
	{
		window.history.pushState(url_data[0], url_data[0], url_data[1]);
	}
}
