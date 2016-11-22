<?php

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'My Console Application',
    'preload'=>array('log'),
    'import'=>array(
        'application.models.*',
        'application.components.*',
    ),
    'params'=>array(
        // exclude param from log output.
        'exclude_param' => array(
        ),
    ),
);
