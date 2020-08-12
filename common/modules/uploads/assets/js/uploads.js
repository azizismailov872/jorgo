var item_id = 0;
var height = 0;

$(document).ready(function()
{
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

function set_success_result(res, url, result_id, alert_id, msg)
{
	if(url == 'delete-file')
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
}

function file_upload(data, item_id)
{
	item_id += 1;
	height = parseInt(data.result.thumbnail.height) + 20;
							
	if($("#files-upload[file_type=" + data.result.upload_type + "] .file_wrap").length > 0)
	{	
		$("<div class=\"file_wrap\" file=\"" + data.result.file + "\" style=\"width:" + data.result.thumbnail.width + "px; height:" + height + "px;\" category=\"'.$category.'\" file_type=\"" + data.result.upload_type + "\" item_id=\"" + item_id + "\"><div align=\"right\"><a class=\"file_delete\" href=\"#\"><span class=\"glyphicon glyphicon-trash\"></span></a></div><div><img src=\"" + data.result.name + "\" style=\"width:" + data.result.thumbnail.width + "px; height:" + data.result.thumbnail.height + "px;\"></div></div>").insertAfter($("#files-upload[file_type=" + data.result.upload_type + "] .file_wrap:last"));
	}
	else
	{	
		$("#files-upload[file_type=" + data.result.upload_type + "]").append("<div class=\"file_wrap\" file=\"" + data.result.file + "\" style=\"width:" + data.result.thumbnail.width + "px; height:" + height + "px;\" category=\"'.$category.'\" file_type=\"" + data.result.upload_type + "\" item_id=\"" + item_id + "\"><div align=\"right\"><a class=\"file_delete\" href=\"#\"><span class=\"glyphicon glyphicon-trash\"></span></a></div><div><img src=\"" + data.result.name + "\" style=\"width:" + data.result.thumbnail.width + "px; height:" + data.result.thumbnail.height + "px;\"></div></div>");
	}
}
