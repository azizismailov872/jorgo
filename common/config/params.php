<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'defaultThemeLayout' => '@app/themes/jorgo/views/layouts/',
    'defaultTheme' => 'black',
    'valid_image_types'=>["gif", "jpg", "jpeg", "png"],
	'upload_dir' => [
		'content_item'=>['model'=>'Content', 'uploads'=>'uploads', 'dir'=>'content_item', 'alias'=>'content_uploads_dir', 'tmp'=>'tmp', 'image_sizes'=>
		[
			'thumbnail'=>['width'=>400, 'height'=>121, 'dir'=>'origin'], 
			'origin_image'=>['width'=>1320, 'height'=>400, 'dir'=>'origin'],
		]],
		'news_item'=>['model'=>'News', 'uploads'=>'uploads', 'dir'=>'news_item', 'alias'=>'news_uploads_dir', 'tmp'=>'tmp', 'image_sizes'=>
		[
			'thumbnail'=>['width'=>413, 'height'=>125, 'dir'=>'origin'], 
			'origin_image'=>['width'=>1320, 'height'=>400, 'dir'=>'origin'],
		]],
	],
];
