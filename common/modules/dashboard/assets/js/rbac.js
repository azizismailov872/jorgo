$(document).ready(function()
{
	$('#create-role-form').on('beforeSubmit', function()
	{
		url = 'create-role';
		data = $(this).serialize();
		alert_id = 'add-role-container';
		result_id = '';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$("body").on("click", "#delete-role", function(event)
	{
		item = $("#delete-role-list option:selected").val();
		data = {item:item};
		url = 'delete-role';
		alert_id = 'delete-role-container';
		result_id = 'index-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false
	});
	
	$('#create-module-form').on('beforeSubmit', function()
	{
		data = $(this).serialize();
		url = 'create-module';
		alert_id = 'add-module-container';
		result_id = 'module';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$("body").on("click", "#delete-module", function(event)
	{
		item = $("#delete-module-list option:selected").val();
		data = {item:item};
		url = 'delete-module';
		alert_id = 'delete-module-container';
		result_id = 'index-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false
	});
	
	$('#inherit-role-id').on('change', function() 
	{
		url = 'check-inherit-role';
		data = $(this).serialize();
		alert_id = 'inherit-role-container';
		result_id = 'role-list-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$('#delete-inherit-role-id').on('change', function() 
	{
		url = 'get-inherit-roles';
		data = $(this).serialize();
		alert_id = 'delete-inherit-role-container';
		result_id = 'delete-role-list-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$('#inherit-role-form').on('beforeSubmit', function()
	{
		url = 'inherit-role';
		data = $(this).serialize();
		alert_id = 'delete-inherit-role-container';
		result_id = 'index-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$('#delete-inherit-role-form').on('beforeSubmit', function()
	{
		url = 'delete-inherit-roles';
		data = $(this).serialize();
		alert_id = 'delete-inherit-role-container';
		result_id = 'delete-role-list-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});
	
	$('#create-permissions-form').on('beforeSubmit', function()
	{
		url = 'create-permission';
		data = $(this).serialize();
		alert_id = 'add-permissions-container';
		result_id = 'index-wrapper';
		
		make_action(url, data, alert_id, result_id, true);
		
		return false;
	});	
});
