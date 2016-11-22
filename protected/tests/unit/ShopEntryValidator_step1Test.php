<?php

class ShopEntryValidator_step1Test extends CDbTestCase
{
    // personal data
    // name1, name2
    public function testName_normal()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1', 'name2',);

        error_log('通常の名前');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1 = '品川';
            $model->name2 = '良子';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
        }
    }

    public function testName_halfwidth_kana()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1', 'name2',);

        error_log('半角文字を全角に変換する');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1 = 'ｼﾅｶﾞﾜ';
            $model->name2 = 'ﾘｮｳｺ';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
            $this->assertEquals($model->name1, 'シナガワ');
            $this->assertEquals($model->name2, 'リョウコ');
        }
    }

    public function testName_not_sjis()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1', 'name2',);

        error_log('特殊文字の禁止');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1 = 'Ⅰ';
            $model->name2 = 'Ⅱ';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testName_blank()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1', 'name2',);

        error_log('空白の禁止');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1 = ' 　';
            $model->name2 = ' 　';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
            $this->assertEquals($model->name1, '');
            $this->assertEquals($model->name2, '');
        }
    }

    public function testName_length()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('__c_name',);

        error_log('文字数制限(<=256byte)');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1 = 'あいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえ';
            $model->name2 = 'あいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいう';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
            $this->assertEquals(256, $this->countSjisWidth($model->__c_name));
        }
    }

    public function testName_length_too_long()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('__c_name',);

        error_log('文字数制限(<=256byte)');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1 = 'あいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえ';
            $model->name2 = 'あいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえ';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
            $this->assertEquals(258, $this->countSjisWidth($model->__c_name));
        }
    }

    // name1_kana, name2_kana
    public function testNameKana_fullwidth_to_halfwidth()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1_kana', 'name2_kana',);

        error_log('通常の名前');
        error_log('全角文字を半角に変換する');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1_kana = 'シナガワ';
            $model->name2_kana = 'リョウコ';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);

            $this->assertEquals('ｼﾅｶﾞﾜ', $model->name1_kana);
            $this->assertEquals('ﾘﾖｳｺ', $model->name2_kana);
        }
    }

    public function testNameKana_small_char()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1_kana', 'name2_kana',);

        error_log('小文字の変換');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1_kana = 'ｯｬｭｮ';
            $model->name2_kana = 'ｯｬｭｮ';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
            $this->assertEquals('ﾂﾔﾕﾖ', $model->name1_kana);
            $this->assertEquals('ﾂﾔﾕﾖ', $model->name2_kana);
        }
    }

    public function testNameKana_halfwidth_exception()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1_kana', 'name2_kana',);

        error_log('一部カナの禁止');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1_kana = 'ｦ';
            $model->name2_kana = 'ｦ';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testNameKana_blank()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1_kana', 'name2_kana',);

        error_log('空白の禁止');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1_kana = ' 　';
            $model->name2_kana = ' 　';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testNameKana_length()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1_kana', 'name2_kana', '__c_name_kana',);

        error_log('文字数制限(<=256byte)');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1_kana = 'あいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきく';
            $model->name2_kana = 'あいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかき';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
            $this->assertEquals(256, $this->countUtf8($model->__c_name_kana));
        }
    }

    public function testNameKana_length_too_long()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1_kana', 'name2_kana', '__c_name_kana',);

        error_log('文字数制限(<=256byte)');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1_kana = 'あいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきく';
            $model->name2_kana = 'あいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきくけこさしすせそたちつてとあいうえおかきく';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
            $this->assertEquals(257, $this->countUtf8($model->__c_name_kana));
        }
    }

    // name1_alphabet, name2_alphabet
    public function testNameAlphabet_normal()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1_alphabet', 'name2_alphabet',);

        error_log('通常の名前');
        error_log('大文字に変換');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1_alphabet = 'Shinagawa';
            $model->name2_alphabet = 'Ryoko';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);

            $this->assertEquals('SHINAGAWA', $model->name1_alphabet);
            $this->assertEquals('RYOKO', $model->name2_alphabet);
        }
    }

    public function testNameAlphabet_not_alphabet()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1_alphabet', 'name2_alphabet',);

        error_log('アルファベット以外');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1_alphabet = '品川';
            $model->name2_alphabet = '良子';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testNameAlphabet_diacritical()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1_alphabet', 'name2_alphabet',);

        error_log('アクセント記号付き');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1_alphabet = 'á';
            $model->name2_alphabet = 'á';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testNameAlphabet_length()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1_alphabet', 'name2_alphabet','__c_name_alphabet');

        error_log('文字数制限(<=256byte)');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1_alphabet = 'abcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefgh';
            $model->name2_alphabet = 'abcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefg';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
            $this->assertEquals(256, $this->countSjisWidth($model->__c_name_alphabet));
        }
    }

    public function testNameAlphabet_length_too_long()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('name1_alphabet', 'name2_alphabet','__c_name_alphabet');

        error_log('文字数制限(<=256byte)');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->name1_alphabet = 'abcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefgh';
            $model->name2_alphabet = 'abcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefgh';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
            $this->assertEquals(257, $this->countSjisWidth($model->__c_name_alphabet));
        }
    }

    // birthday
    public function testBirthday_normal()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('birthday');

        error_log('通常の日付');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->birthday = '1980-04-30';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
        }
    }

    public function testBirthday_future()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('birthday');

        error_log('未来の日付');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->birthday = date('Y-m-d', strtotime('+1day'));
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testBirthday_invalid()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('birthday');

        error_log('存在しない日付');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->birthday = '2001-02-29';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testBirthday_empty()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('birthday');

        error_log('空白の禁止');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->birthday = null;
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    // zipcode
    public function testZipcode_normal()
    {
        $scenarios = array('sp01','insert_cor','insert_pri');
        $validate_attrs = array('zipcode');

        error_log('通常');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->zipcode = '111-2222';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
        }
    }

    public function testZipcode_invalid1()
    {
        $scenarios = array('sp01','insert_cor','insert_pri');
        $validate_attrs = array('zipcode');

        error_log('長い');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->zipcode = '1111-2222';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testZipcode_invalid2()
    {
        $scenarios = array('sp01','insert_cor','insert_pri');
        $validate_attrs = array('zipcode');

        error_log('長い');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->zipcode = '111-22222';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testZipcode_invalid3()
    {
        $scenarios = array('sp01','insert_cor','insert_pri');
        $validate_attrs = array('zipcode');

        error_log('短い');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->zipcode = '111-222';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testZipcode_invalid4()
    {
        $scenarios = array('sp01','insert_cor','insert_pri');
        $validate_attrs = array('zipcode');

        error_log('短い');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->zipcode = '11-2222';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testZipcode_invalid5()
    {
        $scenarios = array('sp01','insert_cor','insert_pri');
        $validate_attrs = array('zipcode');

        error_log('逆転');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->zipcode = '1111-222';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testZipcode_invalid6()
    {
        $scenarios = array('sp01','insert_cor','insert_pri');
        $validate_attrs = array('zipcode');

        error_log('空白');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->zipcode = '1111-222';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }
    // zipcode pc
    public function testZipcode_pc_normal()
    {
        $scenarios = array('pc01');
        $validate_attrs = array('zipcode');

        error_log('通常');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->__zipcode1 = '111';
            $model->__zipcode2 = '2222';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res, $model->zipcode);
        }
    }

    public function testZipcode_pc_invalid1()
    {
        $scenarios = array('pc01');
        $validate_attrs = array('zipcode');

        error_log('長い');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->__zipcode1 = '1111';
            $model->__zipcode2 = '2222';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testZipcode_pc_invalid2()
    {
        $scenarios = array('pc01');
        $validate_attrs = array('zipcode');

        error_log('長い');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->__zipcode1 = '111';
            $model->__zipcode2 = '22222';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testZipcode_pc_invalid3()
    {
        $scenarios = array('pc01');
        $validate_attrs = array('zipcode');

        error_log('短い');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->__zipcode1 = '111';
            $model->__zipcode2 = '222';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testZipcode_pc_invalid4()
    {
        $scenarios = array('pc01');
        $validate_attrs = array('zipcode');

        error_log('短い');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->__zipcode1 = '11';
            $model->__zipcode2 = '2222';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testZipcode_pc_invalid5()
    {
        $scenarios = array('pc01');
        $validate_attrs = array('zipcode');

        error_log('逆転');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->__zipcode1 = '1111';
            $model->__zipcode2 = '222';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testZipcode_pc_invalid6()
    {
        $scenarios = array('pc01');
        $validate_attrs = array('zipcode');

        error_log('空白');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->__zipcode1 = null;
            $model->__zipcode2 = null;
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    // address
    public function testAddress_normal()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1', 'address2');

        error_log('通常の住所');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1 = '神奈川県';
            $model->address2 = '神奈川県';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
        }
    }

    public function testAddress_halfwidth_kana()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1', 'address2');

        error_log('半角文字を全角に変換する');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1 = 'ABC';
            $model->address2 = 'ABC';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
            $this->assertEquals('ＡＢＣ', $model->address1);
            $this->assertEquals('ＡＢＣ', $model->address2);
        }
    }

    public function testAddress_not_sjis()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1', 'address2');

        error_log('特殊文字の禁止');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1 = 'Ⅰ';
            $model->address2 = 'Ⅰ';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testAddress_blank()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1', 'address2');

        error_log('特殊文字の禁止');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1 = ' 　';
            $model->address2 = ' 　';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testAddress_length()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1', 'address2');

        error_log('文字数制限(<=64byte)');
        error_log('文字数制限(<=192byte)');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1 = 'あいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあい';
            $model->address2 = 'あいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおか';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
            $this->assertEquals(64, $this->countSjisWidth($model->address1));
            $this->assertEquals(192, $this->countSjisWidth($model->address2));
        }
    }

    public function testAddress_length_too_long()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1', 'address2');

        error_log('文字数制限(<=64byte)');
        error_log('文字数制限(<=192byte)');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1 = 'あいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいう';
            $model->address2 = 'あいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかき';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
            $this->assertEquals(66, $this->countSjisWidth($model->address1));
            $this->assertEquals(194, $this->countSjisWidth($model->address2));
        }
    }

    // address kana
    public function testAddressKana_fullwidth_to_halfwidth()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1_kana', 'address2_kana');

        error_log('通常の住所');
        error_log('全角文字を半角に変換する');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1_kana = 'カナガワケン';
            $model->address2_kana = 'カナガワケン';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);

            $this->assertEquals('ｶﾅｶﾞﾜｹﾝ', $model->address1_kana);
            $this->assertEquals('ｶﾅｶﾞﾜｹﾝ', $model->address2_kana);
        }
    }

    public function testAddressKana_small_char()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1_kana', 'address2_kana');

        error_log('半角文字を全角に変換する');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1_kana = 'ｯｬｭｮ';
            $model->address2_kana = 'ｯｬｭｮ';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
            $this->assertEquals('ﾂﾔﾕﾖ', $model->address1_kana);
            $this->assertEquals('ﾂﾔﾕﾖ', $model->address2_kana);
        }
    }

    public function testAddressKana_halfwidth_exception()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1_kana', 'address2_kana');

        error_log('一部カナの禁止');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1_kana = 'ｦ';
            $model->address2_kana = 'ｦ';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testAddressKana_blank()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1_kana', 'address2_kana');

        error_log('特殊文字の禁止');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1_kana = ' 　';
            $model->address2_kana = ' 　';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
        }
    }

    public function testAddress_lKanaength()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1_kana', 'address2_kana');

        error_log('文字数制限(<=64byte)');
        error_log('文字数制限(<=192byte)');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1_kana = 'あいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえ';
            $model->address2_kana = 'あいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあい';
            $res = $model->validate($validate_attrs);
            $this->assertTrue($res);
            $this->assertEquals(64, $this->countUtf8($model->address1_kana));
            $this->assertEquals(192, $this->countUtf8($model->address2_kana));
        }
    }

    public function testAddressKana_length_too_long()
    {
        $scenarios = array('sp01','pc01','insert_cor','insert_pri');
        $validate_attrs = array('address1_kana', 'address2_kana');

        error_log('文字数制限(<=64byte)');
        error_log('文字数制限(<=192byte)');

        foreach ($scenarios as $scenario) {
            $model = $this->loadModel($scenario);
            $model->address1_kana = 'あいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえお';
            $model->address2_kana = 'あいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいう';
            $res = $model->validate($validate_attrs);
            $this->assertFalse($res);
            $this->assertEquals(65, $this->countUtf8($model->address1_kana));
            $this->assertEquals(193, $this->countUtf8($model->address2_kana));
        }
    }

    private function countSjisWidth($str) {
        $sjis = mb_convert_encoding($str, 'sjis-win', 'utf8');
        return strlen($sjis);
    }

    private function countUtf8($str) {
        return mb_strlen($str, 'utf8');
    }

    private function loadModel($scenario=null) {
        $model = new ShopEntry($scenario);
        return $model;
    }
}
