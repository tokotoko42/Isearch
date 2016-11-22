<?php

class ShopEntryTest extends CTestCase
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

    public function testHooks()
    {
        $model = $this->loadModel();
        $model->status = ShopEntry::STATUS_WAIT_IMAGE;
        $this->assertInternalType('boolean', $model->save());
    }

    public function testGetters()
    {
        $model = $this->loadModel();
        $model->status = ShopEntry::STATUS_WAIT_IMAGE;
        $this->assertInternalType('array', $model->statusNames);
        foreach ($model->statusNames as $n) {
            $this->assertInternalType('string', $n);
        }
        $this->assertInternalType('string', $model->statusName);
    }

    public function testGenerateEntryCode()
    {
        $model = new ShopEntry;
        $model->insert();
        $model->generateEntryCode();
        var_dump($model->entry_code);

        $model = new ShopEntry;
        $model->is_corporate = true;
        $model->insert();
        $model->generateEntryCode();
        var_dump($model->entry_code);

        $model = new ShopEntry;
        $model->insert();
        $model->generateEntryCode();
        var_dump($model->entry_code);

        $model = new ShopEntry;
        $model->is_corporate = true;
        $model->insert();
        $model->generateEntryCode();
        var_dump($model->entry_code);
    }
    /**
     * @return ShopEntry
     */
    private function loadModel($scenario=null)
    {
        $model = ShopEntry::model();
        $this->assertInstanceOf('ShopEntry', $model, 'Failed to load ShopEntry.');
        $this->assertEquals($model->tableName(), 'shop_entry', 'Confirm table name.');
        $this->scenario = $scenario;
        $this->assertEquals($this->scenario, $scenario, 'Failed to set scenario');
        return $model;
    }
}

