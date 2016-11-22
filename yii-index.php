<?php

error_reporting(E_ALL|E_STRICT);
// detect environment
$env = $_SERVER['ENV'];
$yii = 'protected/yii/framework/yii.php';
switch ($env) {
case 'PRO': // 本番環境
    $config = dirname(__FILE__) . '/protected/config/_r_product.php';
    defined('YII_DEBUG') or define('YII_DEBUG', false);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 0);
    break;
case 'LOC': // Local開発環境
    $config = dirname(__FILE__) . '/protected/config/_r_localhost.php';
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
    break;
default:
    throw new Exception('Failed to detect the environment.');
    break;
}

require_once($yii);
Yii::createWebApplication($config)->run();
