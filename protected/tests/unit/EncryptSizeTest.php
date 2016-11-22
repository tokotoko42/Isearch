<?php

class EncryptSizeTest extends CTestCase
{
    public function testLongHWKana()
    {
        $text = 'ｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲｳｴｵｶｷｸｹｺｱｲ';
        $this->assertEquals(254, mb_strlen($text, 'utf8'));
        $enc = $this->encrypt($text);
        $this->assertEquals($text, $this->decrypt($enc));
        $this->assertLessThanOrEqual(256, strlen($enc));
    }

    public function testLongASCII()
    {
        $text = 'abcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdef';
        $this->assertEquals(256, mb_strlen($text, 'utf8'));
        $enc = $this->encrypt($text);
        $this->assertEquals($text, $this->decrypt($enc));
        $this->assertLessThanOrEqual(256, strlen($enc));
    }

    public function testLongSJIS()
    {
        $text = 'あいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおかきくけこあいうえおか';
        $this->assertEquals(126, mb_strlen($text, 'utf8'));
        $enc = $this->encrypt($text);
        $this->assertEquals($text, $this->decrypt($enc));
        $this->assertLessThanOrEqual(256, strlen($enc));
    }

    private function encrypt($value)
    {
        return base64_encode(Yii::app()->crypt->encrypt($value));
    }

    private function decrypt($value)
    {
        return Yii::app()->crypt->decrypt(base64_decode($value));
    }
}

