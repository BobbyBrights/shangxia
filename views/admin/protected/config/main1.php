<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'lemans',
    "defaultController" => "index",
    'language'=>'en_us',
    'sourceLanguage'=> "00",
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.easyimage.EasyImage',
    ),
    'modules' => array(
        'api',
    // uncomment the following to enable the Gii tool
    /*
      'gii'=>array(
      'class'=>'system.gii.GiiModule',
      'password'=>'Enter Your Password Here',
      // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters'=>array('127.0.0.1','::1'),
      ),
     */
    ),
    // application components
    'components' => array(
        "ip" => array(
            "class" => "ext.ip.IPToLatlng",
        ),
        "weibo" => array(
            "class" => "ext.weibo.weibo",
            "app_key" => "2056250808",
            "app_secret" => "03a9c1cb0aa9666eaba0d4b7f2d44bad",
        ),
        'easyImage' => array(
            'class' => 'application.extensions.easyimage.EasyImage',
            'driver' => 'GD',
            'quality' => 100,
            'cachePath' => '/upload',
            'cacheTime' => 2592000,
        //'retinaSupport' => false,
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format	
        'urlManager' => array(
            'urlFormat' => 'path',
              'urlSuffix'=>'.html',
            'rules' => array(  
                '<action:\w+>' => 'index/<action>',
                "video/<video:\d+>" => "index/video",
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>', 
            ),
        ),
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=127.0.0.1;dbname=shangxia',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'hdg',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'index/error',
        ),
        "session" => array(
            'class' => 'system.web.CDbHttpSession',
            'connectionID' => 'db',
            'sessionName' => 'LUCSID',
            'sessionTableName' => 'session',
            'timeout' => 3600 * 24,
            'cookieMode' => 'allow',
            'cookieParams' => array(
                'lifetime' => 3600 * 24,
                'path' => '/',
                'httpOnly' => true),
        ),
        "cache" => array(
            "class" => 'system.caching.CFileCache',
            "cachePath" => dirname(dirname(__FILE__))."/cache",
            "cacheFileSuffix" => "_cache",
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace',
                ),
            // uncomment the following to show log messages on web pages
              array(
              'class'=>'CWebLogRoute',
              "enabled" => YII_DEBUG
              ),
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'adminEmail' => 'jziwenchen@gmail.com', 
        "uploadDir" => (dirname(dirname(dirname(__FILE__))))."/upload",
        "close_error" => FALSE,
        "brands" => array(
            "18" => "Dazzle", "19" =>  "Diamond Dazzle", "20" => "D'zzit"
        ),
    ),
);
