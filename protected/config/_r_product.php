<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
    array(
        'components'=>array(
            'db'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=isearch',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => 'newpassword',
                'charset' => 'utf8',
            ),
            'log' => array(
                'routes'=>array(
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'info, debug',
                        'logFile'=>'debug.log',
                    ),
                )
            ),
        ),
        'params' => array(
        ),
    )
);
