<?php

class BankBranchTest extends CTestCase
{
    public function testBehaviorList()
    {
        $model = $this->loadModel();
        $this->assertInternalType('array', $model->behaviors());
        foreach ($model->behaviors() as $n => $b) {
            $this->assertInternalType('string', $b['class'], sprintf("[class] is required: behavior '%s'", $n));
        }
    }

    public function testRules()
    {
        $model = $this->loadModel();
        $this->assertInternalType('array', $model->rules());
    }

    public function testRelations()
    {
        $model = $this->loadModel();
        $this->assertInternalType('array', $model->relations());

        $model->bank_code = '0001';
        $b = $model->bank;
        $this->assertInstanceOf('Bank', $b);
    }

    public function testAttributeLabels()
    {
        $model = $this->loadModel();
        $this->assertInternalType('array', $model->attributeLabels());
    }

    public function testSearch()
    {
        $model = $this->loadModel();
        $this->assertInstanceOf('CActiveDataProvider', $model->search());
    }

    /**
     * @return BankBranch
     */
    private function loadModel($scenario=null)
    {
        $model = BankBranch::model();
        $this->assertInstanceOf('BankBranch', $model, 'Failed to load BankBranch.');
        $this->assertEquals($model->tableName(), 'bank_branch', 'Confirm table name.');
        $this->scenario = $scenario;
        $this->assertEquals($this->scenario, $scenario, 'Failed to set scenario');
        return $model;
    }
}

