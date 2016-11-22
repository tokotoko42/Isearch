<?php

class RakutenMailerDummyTest extends CTestCase
{
    public function testSend()
    {
        $mailer = Yii::app()->mailer_dummy;
        $mailer->send('transfer_accepted', 'hiko@hymena.jp', array(
            'shop'=>array(
                'shop_code'=>'*shop_code*',
                'name1'=>'name1',
                'name2'=>'name2'
            )
        ));
    }
}
