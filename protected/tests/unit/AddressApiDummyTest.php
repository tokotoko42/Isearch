<?php

class AddressApiDummyTest extends CTestCase
{
    public function testEmpty()
    {
        $api = Yii::app()->address_api;
        $zipcode = '';
        $this->setExpectedException('InvalidArgumentException');
        $api->query($zipcode);
    }

    public function testQuery()
    {
        $api = Yii::app()->address_api;
        $zipcode = '1060031';
        $addr = $api->query($zipcode);
        $as = array(
            'state',
            'city',
            'street',
            'state_kana',
            'city_kana',
            'street_kana',
        );
        foreach ($as as $a) {
            $this->assertNotEmpty($addr[$a]);
        }
    }
}
