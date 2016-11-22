<?php
return CMap::mergeArray(
    require(dirname(__FILE__) . '/console.php'),
    array(
        'components'=>array(
            'db'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=isearch',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => 'newpassword',
                'charset' => 'utf8',
            ),
        ),
        'params' => array(
            'env' => 'pro',
        ),
    )
);
