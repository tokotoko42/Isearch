<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
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
            // メモリ使用量閾値
            'memory_usage' => array(
                'all' => array(
                    'warning' => 102000000,
                    'error'   => 128000000,
                ),
            ),
        ),
    )
);
