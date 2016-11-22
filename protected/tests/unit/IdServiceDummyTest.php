<?php

class IdServiceDummyTest extends CTestCase
{
    public function testInvalid()
    {
        $id = Yii::app()->id_service->with('sp');
        $id->setRa('invalid_ra');

        $this->assertInternalType('boolean', $id->isRakutenBankMember(), __LINE__);
        $this->assertInternalType('boolean', $id->isRakutenCardMember(), __LINE__);
    }

    public function testSpRa0001()
    {
        $id = Yii::app()->id_service->with('sp');
        $id->setRa('RA0001');
        $info = $id->idInfo;
        $this->checkProfile($info);
        $this->checkBankAccount($info);
    }

    public function testSpRa0003()
    {
        $id = Yii::app()->id_service->with('sp');
        $id->setRa('RA0003');
        $info = $id->idInfo;
        $this->checkProfile($info);
        $this->checkBankAccount($info);
    }

    public function testSpRa0002()
    {
        $id = Yii::app()->id_service->with('sp');
        $id->setRa('RA0002');
        $info = $id->idInfo;
        $this->checkProfile($info);
        $this->checkBankAccountEmpty($info);
    }

    public function testSpRa0004()
    {
        $id = Yii::app()->id_service->with('sp');
        $id->setRa('RA0004');
        $info = $id->idInfo;
        $this->checkProfile($info);
        $this->checkBankAccountEmpty($info);
    }

    public function testPcRa0001()
    {
        $id = Yii::app()->id_service->with('pc');
        $id->setRa('RA0001');
        $info = $id->idInfo;
        $this->checkProfile($info);
        $this->checkBankAccount($info);
    }

    public function testPcRa0003()
    {
        $id = Yii::app()->id_service->with('pc');
        $id->setRa('RA0003');
        $info = $id->idInfo;
        $this->checkProfile($info);
        $this->checkBankAccount($info);
    }

    public function testPcRa0002()
    {
        $id = Yii::app()->id_service->with('pc');
        $id->setRa('RA0002');
        $info = $id->idInfo;
        $this->checkProfile($info);
        $this->checkBankAccountEmpty($info);
    }

    public function testPcRa0004()
    {
        $id = Yii::app()->id_service->with('pc');
        $id->setRa('RA0004');
        $info = $id->idInfo;
        $this->checkProfile($info);
        $this->checkBankAccountEmpty($info);
    }

    private function checkProfile($info)
    {
        $cs = array(
            'birthday',
            'firstName',
            'firstNameKana',
            'gender',
            'surName',
            'surNameKana',
        );
        foreach ($cs as $c) {
            $this->assertNotEmpty($info->uniqueProfileModel->$c);
        }
        $cs = array(
            'easyId',
            'postalCode',
            'state',
            'city',
            'street',
        );
        foreach ($cs as $c) {
            $this->assertNotEmpty($info->profileModelArray[0]->$c);
        }
        $this->assertTrue(!empty($info->profileModelArray[0]->email) || !empty($info->profileModelArray[0]->mobileEmail));
        $this->assertTrue(!empty($info->profileModelArray[0]->phone) || !empty($info->profileModelArray[0]->mobilePhone));
    }

    private function checkBankAccount($info)
    {
        $cs = array(
            'bankId',
            'branchId',
            'number',
            'owner',
        );
        foreach ($cs as $c) {
            $this->assertNotEmpty($info->accountModel->$c);
        }
        $cs = array(
            'bankId',
            'branchId',
            'number',
        );
        foreach ($cs as $c) {
            $this->assertTrue(is_int($info->accountModel->$c));
        }
    }

    private function checkBankAccountEmpty($info)
    {
        $cs = array(
            'bankId',
            'branchId',
            'number',
            'owner',
        );
        foreach ($cs as $c) {
            $this->assertTrue(empty($info->accountModel->$c));
        }
    }
}
