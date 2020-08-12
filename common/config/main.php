<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
			'translations' => [
				'menu*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@common/messages',
					'sourceLanguage' => 'en-En',
					'fileMap' => [
						'messages' => 'menu.php',
					],
					'forceTranslation' => true,
				],
				'messages*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@common/messages',
					'sourceLanguage' => 'en-En',
					'fileMap' => [
						'messages' => 'messages.php',
					],
					'forceTranslation' => true,
				],
				'form*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@common/messages',
					'sourceLanguage' => 'en-En',
					'fileMap' => [
						'messages' => 'form.php',
					],
					'forceTranslation' => true,
				],
				'mail*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@common/messages',
					'sourceLanguage' => 'en-En',
					'fileMap' => [
						'messages' => 'mail.php',
					],
				],
			],
		],
    ],
    'language' => 'en-En',
];
