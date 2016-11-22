<?php
return CMap::mergeArray(
    require(dirname(__FILE__) . '/console.php'),
    array(
        'components'=>array(
            'db'=>array(
                'connectionString' => 'mysql:host=127.0.0.1;dbname=isearch',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => 'test',
                'charset' => 'utf8',
            ),
        ),
        'params' => array(
            'env' => 'loc',
        ),
    )
);
