<?php

class BankTest extends CTestCase
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

        $model->code = '0001';
        $bs = $model->branches;
        $this->assertInternalType('array', $bs);
        $this->assertInstanceOf('BankBranch', $bs[0]);
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
     * @return Bank
     */
    private function loadModel($scenario=null)
    {
        $model = Bank::model();
        $this->assertInstanceOf('Bank', $model, 'Failed to load Bank.');
        $this->assertEquals($model->tableName(), 'bank', 'Confirm table name.');
        $this->scenario = $scenario;
        $this->assertEquals($this->scenario, $scenario, 'Failed to set scenario');
        return $model;
    }
}

