<?php

class ShopTest extends CDbTestCase
{
    public function testPartialInsert()
    {
        $rows = array();
        $rows[] = array(
            'easy_id' => 1234567,
            'login_id' => sha1('shopidp'),
            'password' => sha1('shopps'),
            'shop_name' => '楽天バーガーP',
            'profile_image' => 'https://round.xia.jp/stub/image.jpg',
            'shop_code' => null,
        );
        $rows[] = array(
            'easy_id' => 123456,
            'login_id' => sha1('shopid'),
            'password' => sha1('shopps'),
            'shop_name' => '楽天バーガー',
            'profile_image' => 'https://round.xia.jp/stub/image2.jpg',
            'shop_code' => 5000000000000,
        );
        foreach ($rows as $row) {
            $s = new Shop;
            $s->attributes = $row;
            $s->save();
            var_dump($s->errors);
        }
    }

    private function loadModel($scenario=null)
    {
        $model = new Shop($scenario);
        $this->assertTrue($model instanceof Shop);
        return $model;
    }
}

