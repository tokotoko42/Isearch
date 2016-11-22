<?php

class DecryptCommand extends BatchBase
{
    public $run_multiple = false;
    public function run($args)
    {
        $encrypted = $args[0];
        if (strlen($encrypted)%4==0 && preg_match('/^[a-zA-Z0-9\/\+]+={0,3}$/', $encrypted)) {
            $encrypted = Yii::app()->crypt->decrypt(base64_decode($encrypted));
            if (strlen($encrypted)%4==0 && preg_match('/^[a-zA-Z0-9\/\+]+={0,3}$/', $encrypted)) {
                $encrypted = Yii::app()->crypt->decrypt(base64_decode($encrypted));
            }
        }
        echo "$encrypted \n";
        
    }
}
