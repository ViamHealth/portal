<?php

// uncomment the following to define a path alias
//Yii::setPathOfAlias('viam_assets','../modules/viam/assets');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Viam Health',


	'theme'=>'viam', // requires you to copy the theme under your themes directory

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
    'application.components.*',
    //'application.modules.user.models.*',
    //'application.modules.user.components.*',
    'application.modules.viam.components.*',
    'application.modules.viam.models.*',
    'ext.restfullyii.components.*' ,
  ),

	'modules'=>array(
				'viam'=>array(),
        /*'user'=>array(
            # encrypting method (php hash function)
            'hash' => 'md5',
            # send activation email
            'sendActivationMail' => false,
            # allow access for non-activated users
            'loginNotActiv' => false,
            # activate user on registration (only sendActivationMail = false)
            'activeAfterRegister' => true,
            # automatically login from registration
            'autoLogin' => true,
            # registration path
            'registrationUrl' => array('/user/registration'),
            # recovery password path
            'recoveryUrl' => array('/user/recovery'),
            # login form path
            'loginUrl' => array('/user/login'),
            # page after login
            'returnUrl' => array('/user/profile'),
            # page after logout
            'returnLogoutUrl' => array('/user/login'),
        ),*/	
		// uncomment the following to enable the Gii tool
    'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'vi@m',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
            'generatorPaths'=>array(
                'bootstrap.gii',
            ),
        ),
	),
 
	// application components
	'components'=>array(
		'urlManager'=>array(
		    'urlFormat'=>'path',
		    'showScriptName'=>false,
		     'caseSensitive'=>false,        
		),		
		'user'=>array(
			// enable cookie-based authentication
            'class' => 'WebUser',
		),
    'bootstrap'=>array(
        'class'=>'bootstrap.components.Bootstrap',
        'responsiveCss' => true,
        'fontAwesomeCss' => true,
    ),		
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>require(dirname(__FILE__).'/../extensions/restfullyii/config/routes.php'),
			/*'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),*/
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=viam',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
/*                	'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                	'ipFilters'=>array('127.0.0.1','192.168.1.215'),
*/
				),
				// uncomment the following to show log messages on web pages
				
				/*array(
					'class'=>'CWebLogRoute',
				),*/
				
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'apiServer' => 'http://127.0.0.1:8080/',
		//TODO:make auth class
		'token'=>'',
	),
);
