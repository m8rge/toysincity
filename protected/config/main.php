<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

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
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(
				'/'=>'site/index',
				'<action:\w+>'=>'site/<action>',
			),
		),
		'db'=>array(
			'connectionString' => 'mysql:host='.$params['dbHost'].';dbname='.$params['dbName'],
			'emulatePrepare' => true,
			'username' => $params['dbLogin'],
			'password' => $params['dbPassword'],
			'charset' => 'utf8',
		),
		/*'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),*/
		/*'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				array(
					'class'=>'CWebLogRoute',
				),
			),
		),*/
	),

	'params'=> array_merge($params, array(
		'md5Salt' => 'ThisIsMymd5Salt(*&^%$#',
	)),
);