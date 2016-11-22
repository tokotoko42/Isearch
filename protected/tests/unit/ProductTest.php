<?php

class ProductTest extends CTestCase
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

    public function testGetters()
    {
        $model = $this->loadModel();

        $this->assertInternalType('array', $model->products);
        foreach ($model->products as $num => $p) {
            $this->assertTrue(is_numeric($p['id']), sprintf('id is not number. given: %s, number: %d', $p['id'], $num));
            $this->assertTrue(is_numeric($p['business_category_id']), sprintf('business_category_id is not number. given: %s, number: %d', $p['business_category_id'], $num));
            $this->assertTrue(is_numeric($p['sort_category_number']), sprintf('sort_category_number is not number. given: %s, number: %d', $p['sort_category_number'], $num));
            $this->assertTrue(is_numeric($p['sort_category_order']), sprintf('sort_category_order is not number. given: %s, number: %d', $p['sort_category_order'], $num));
            $this->assertInternalType('string', $p['name'], sprintf('name is not string. given: %s, number: %d', $p['name'], $num));
        }

        $this->assertInternalType('array', $model->categories);
        foreach ($model->categories as $k => $c) {
            $this->assertInternalType('string', $k);
            $this->assertRegExp('|^\d+-\d+$|', $k);
            $this->assertInternalType('string', $c);
        }
    }

    /**
     * @return ShopEntry
     */
    private function loadModel($scenario=null)
    {
        $model = Product::model();
        $this->assertInstanceOf('Product', $model, 'Failed to load Product.');
        $this->assertEquals($model->tableName(), 'product', 'Confirm table name.');
        $this->scenario = $scenario;
        $this->assertEquals($this->scenario, $scenario, 'Failed to set scenario');
        return $model;
    }
}

