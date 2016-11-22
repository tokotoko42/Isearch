<?php

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'isearch',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.BaseModel',
        'application.models.*',
        'application.components.*',
    ),

    'modules'=>array(
        // uncomment the following to enable the Gii tool
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'isearch',
             // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1','192.168.*'),
        ),
    ),

    // application components
    'components'=>array(
        'request'=>array(
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
            ),
            'showScriptName' => false,
        ),
        'session'=>array(
            'class'=>'IsearchSession',
            'connectionID'=>'db',
            'sessionTableName'=>'session',
            'autoStart'=>false,
            'cookieMode'=>'only',
            'timeout'=> 7776000,
        ),
        'crypt'=>array(
            'class'=>'CSecurityManager',
            'cryptAlgorithm'=>'rijndael-256',
            'encryptionKey'=>'eekkrw0sX11225ueopaaq7crf23jgkig',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                'class'=>'CFileLogRoute',
            ),
        ),
        'viewRenderer' => array(
            'class'=>'ext.yiiext.renderers.dwoo.EDwooViewRenderer',
            'fileExtension' => '.tpl',
        ),
        'securityManager'=>array(
            'validationKey'=>'s5dwer493bda0c0a4333db885aajga8a',
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'version'=>date('YmdHi'),
        'year'=>date('Y'),
    ),
);
