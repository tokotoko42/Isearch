<?php

/**
 * StringUtil Class
 * 文字列ユーティリティクラス
 *
 * @package
 * @subpackage
 * @version $Revision$
 * $Id$
 */
class StringUtil extends CComponent
{

    /**
     * Convert string from UTF-8 to JIS (for mail).
     *
     * @param string $string
     * @return string
     */
    public static function convertStringJIS($string)
    {
        return mb_convert_encoding($string, 'JIS', 'UTF-8');
    }

    /**
     * Decode shop data.
     *
     * @param type $encrypted
     * @return type
     */
    public static function encryptShopData($encrypted)
    {
        if (preg_match('/^[a-zA-Z0-9\/\+]+={0,3}$/', $encrypted)) {
            $encrypted = Yii::app()->crypt->decrypt(base64_decode($encrypted));
            if (preg_match('/^[a-zA-Z0-9\/\+]+={0,3}$/', $encrypted)) {
                $encrypted = Yii::app()->crypt->decrypt(base64_decode($encrypted));
            }
        }
        return $encrypted;
    }
    /*
     * Convert string from UTF-8 to SJIS
     *
     * @param string $string
     * @return string
     */
    public static function convertStringSJIS($string)
    {
        return mb_convert_encoding($string, 'SJIS', 'UTF-8');
    }
}
