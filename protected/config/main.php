<?php

Yii::setPathOfAlias('lib', realpath(dirname(__FILE__).'/../../lib'));

$params = require('params.php');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>$params['siteName'],
	'language' => 'ru',

	'preload'=>array('log'),

	'import'=>array(
		'application.models.*',
		'application.models.forms.*',
		'application.components.*',
		'application.helpers.*',
		'application.widgets.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl'=>array('site/login'),
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'urlSuffix' => '/',
			'showScriptName' => false,
			'rules'=>array(
				'/'=>'site/index',
				'guestbook/'=>'qa/index',
				'catalog/<categoryId:.+>/<itemId:.+>'=>'site/item',
				'catalog/<categoryId:.+>'=>'site/catalog',
				'admin/' => 'admin/admin/index',
				'admin/<controller:\w+>/' => 'admin/admin<controller>',
				'admin/<controller:\w+>/<action:\w+>/' => 'admin/admin<controller>/<action>',
				'<uri:(buy-guide|about)>' => 'site/staticPage',
				'<_a:(login|logout|error|cart|addToCart|search|vendor)>/' => 'site/<_a>',
//				'<controller:\w+>' => '<controller>/index',
//				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			),
		),
		'db'=>array(
			'connectionString' => 'mysql:host='.$params['dbHost'].';dbname='.$params['dbName'],
			'emulatePrepare' => true,
			'username' => $params['dbLogin'],
			'password' => $params['dbPassword'],
			'charset' => 'utf8',
		),
		'request'=>array(
//			'enableCsrfValidation'=>true,
		),
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
		),
		'fs' => array(
			'class' => 'FileSystem'
		),
		'image' => array(
			'class' => 'ext.image.CImageComponent',
			'driver' => $params['imageDriver'],
		),
		'viewRenderer'=>array(
			'class'=>'ext.ETwigViewRenderer',
			'twigPathAlias' => 'lib.twig.lib.Twig',
			'options' => array(
				'autoescape' => true,
			),
			'functions' => array(
				'widget' => array(
					0 => 'TwigFunctions::widget',
					1 => array('is_safe' => array('html')),
				),
			),
		),
		'bootstrap'=>array(
			'class'=>'lib.bootstrap.components.Bootstrap', // assuming you extracted bootstrap under extensions
			'responsiveCss' => true,
		),
		/*'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),*/
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
//				array(
//					'class'=>'CFileLogRoute',
//					'levels'=>'error, warning',
//				),
//				array(
//					'class'=>'CWebLogRoute',
//				),
			),
		),
	),

	'params'=> array_merge($params, array(
		'md5Salt' => 'ThisIsMymd5Salt(*&^%$#',
	)),
);