<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => 
    [
		'dashboard' => [
            'class' => 'common\modules\dashboard\Module'
        ],
		'news' => 
		[
			'class' => 'common\modules\news\Module'
		],
		'content' => 
		[
			'class' => 'common\modules\content\Module'
		],
		'uploads' => [
            'class' => 'common\modules\uploads\Module'
        ],
    ],
    'defaultRoute' => 'dashboard/site/login',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
			'identityClass' => 'common\modules\dashboard\models\AdminUsers',
            'enableAutoLogin' => true,
			'identityCookie' => [
				'name' => '_backendIdentity',
				'httpOnly' => true,
			],
		],
		'session' => [
			'name' => 'BACKENDSESSID',
			'cookieParams' => [
				'path' => '/admin',
			],
		],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
			'theme' => [
				'pathMap' => ['@app/views' => '@app/themes/gentella/views'],
				'baseUrl' => '@web/themes/gentella/views',
			],
		],
		'assetManager' => [
			'bundles' => [
				'yii\web\JqueryAsset' => [
					'js'=>[
						'//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', // добавление вашей версии
					]
				]
			],
		],
		'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
				'site/login'=>'dashboard/site/login',
				'logout'=>'dashboard/site/logout',
				'set-page/<index:[\w\-]+>/<url:[\w\-]+>/<count:\d+>'=>'settings/set-page',
				'users'=>'dashboard/users/index',
				'users-search'=>'dashboard/users/search',
				'users/update'=>'dashboard/users/update',
				'users/update-user'=>'dashboard/users/update-user',
				'users/set-page'=>'dashboard/users/set-page',
				'users/delete-user'=>'dashboard/users/delete',
				'users/profile/<id:\d+>'=>'dashboard/users/view',
				'users/create-user'=>'dashboard/users/create',
				'users/add-user'=>'dashboard/users/add-user',
				'users/create-group'=>'dashboard/users-groups/save',
				'users/edit-group'=>'dashboard/users-groups/edit',
				'users/update-group'=>'dashboard/users-groups/update',
				'users/delete-group'=>'dashboard/users-groups/delete',
				'rbac/roles'=>'dashboard/roles/index',
				'rbac/create-role'=>'dashboard/roles/create-role',
				'rbac/delete-role'=>'dashboard/roles/delete-role',
				'rbac/check-inherit-role'=>'dashboard/roles/check-inherit-role',
				'rbac/inherit-role'=>'dashboard/roles/inherit-role',
				'rbac/get-inherit-roles'=>'dashboard/roles/get-inherit-roles',
				'rbac/delete-inherit-roles'=>'dashboard/roles/delete-inherit-roles',
				'permissions'=>'dashboard/permissions/index',
				'dashboard/permissions/create-module'=>'dashboard/module/create',
				'dashboard/permissions/delete-module'=>'dashboard/module/delete',
				'permissions/create-permission'=>'dashboard/permissions/create-permission',
				'permissions-search'=>'dashboard/permissions/search',
				'permissions/set-page'=>'dashboard/permissions/set-page',
				'delete-permission'=>'dashboard/permissions/delete-permission',
				'news' => 'news/backend-news/index',
				'news/edit/<id:\d+>' => 'news/backend-news/update',
				'news/view/<id:\d+>' => 'news/backend-news/view',
				'news/delete/<id:\d+>' => 'news/backend-news/delete',
				'news/publish/<id:\d+>/<status:\d+>' => 'news/backend-news/status',
				'menu-categories' => 'content/menu-categories/index',
				'content/static-content'=>'content/static-content/index',
				'content/delete-static'=>'content/static-content/delete',
				'content/static-status'=>'content/static-content/status',
				'content/content/create-menu-category'=>'content/menu-categories/save',
				'content/content/edit-menu-category'=>'content/menu-categories/edit',
				'content/content/update-menu-category'=>'content/menu-categories/update',
				'content/content/delete-menu-category'=>'content/menu-categories/delete',
				'content/content/get-menu-category-list'=>'content/menu/get-menu-category-list',
				'content/content/create-menu'=>'content/menu/save',
				'content/content/edit-menu'=>'content/menu/edit',
				'content/content/update-menu'=>'content/menu/update-menu',
				'content/content/delete-menu'=>'content/menu/delete',
				'content/menu/menu-delete'=>'content/menu/delete',
				'content/menu/menu-status'=>'content/menu/status',
				'content/menu-status'=>'content/menu/status',
				'content/menu/order-up'=>'content/menu/order-up',
				'content/content/change-menu-publish'=>'content/menu/change-publish',
				'content/content/publish-menu'=>'content/menu/publish-menu',
				'content/content/create-content'=>'content/content/save',
				'content/delete-content'=>'content/content/delete',
				'content/content/delete-content'=>'content/content/delete',
				'content/content-status'=>'content/content/status',
				'content/static-content/uploads/files-upload/content-upload'=>'uploads/files-upload/content-file-upload',
				'content/content/uploads/files-upload/content-upload'=>'uploads/files-upload/content-file-upload',
				'content/item/image-upload'=>'uploads/files-upload/upload',
				'content/content/delete-file'=>'uploads/files-upload/delete-file',
				'news'=>'news/backend-news/index',
				'news/status'=>'news/backend-news/status',
				'news/delete'=>'news/backend-news/delete',
				'news/backend-news/uploads/files-upload/content-upload'=>'uploads/files-upload/content-file-upload',
				'news/item/image-upload'=>'uploads/files-upload/upload',
				'news/backend-news/delete-file'=>'uploads/files-upload/delete-file',
            ],
        ],
    ],
    'params' => $params,
    'aliases' => [
        '@theme' => '/themes/gentella',
    ],
];
