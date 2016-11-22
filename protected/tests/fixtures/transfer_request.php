<?php

$f = function () {
    $now = time();
    $today = date('Y-m-d', $now);
    $yesterday = date('Y-m-d', $now-60*60*24);
    return array(
        'prev_234459'=>array('target_shop_code'=>'1000', 'requested'=>$yesterday . ' 23:44:59'),
        'prev_234500'=>array('target_shop_code'=>'1001', 'requested'=>$yesterday . ' 23:45:00'),
        'tod_175959' =>array('target_shop_code'=>'1000', 'requested'=>$today     . ' 17:59:59'),
        'tod_180000' =>array('target_shop_code'=>'9000', 'requested'=>$today     . ' 18:00:00'),
        'tod_234459' =>array('target_shop_code'=>'9001', 'requested'=>$today     . ' 23:44:59'),
        'tod_234500' =>array('target_shop_code'=>'9012', 'requested'=>$today     . ' 23:45:00'),
    );
};

return $f();
