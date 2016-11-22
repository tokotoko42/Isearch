<?php

class IdServiceTest extends CTestCase
{
    public function testIsRakutenCard()
    {
        $id = Yii::app()->id_service->with('sp');
        $this->assertFalse($id->isRakutenCard('0000-0000-0000-0000'));
        $this->assertTrue($id->isRakutenCard('4923-7200-0000-0000'));
        $this->assertTrue($id->isRakutenCard('5210-1205-0000-0000'));
        $this->assertTrue($id->isRakutenCard('3584-0300-0000-0000'));

        $this->assertTrue($id->isRakutenCard('3584-0390-0000-8207'));
        $this->assertTrue($id->isRakutenCard('5210-1500-0001-3068'));
        $this->assertTrue($id->isRakutenCard('4297-7000-0002-4652'));
        $this->assertTrue($id->isRakutenCard('4297-6900-0000-0028'));
        $this->assertTrue($id->isRakutenCard('3584-0300-1234-1231'));
    }
}
