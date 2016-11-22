<?php
/**
 *  改善 Log Class.
 *  Compoentからも叩けるようにEmvContlloerから独立させる
 */

// 定数ファイル
Yii::import('application.const.*');
class LogUtil
{
    /**
     * ログ改善出力
     *
     * @param string $token
     * @param string $log_id
     * @param string $log_level
     * @param string $req_id
     * @param string $cls
     * @param string $fnc
     * @param integer $line
     * @param string $message
     */
    public static function setLog($token, $log_id, $log_level, $req_id, $cls, $fnc, $line, $message, $shop_code = '', $agent_id = '')
    {
        $log = array();
        $log[] = str_pad($token, ConstEmv::LEN_TOKEN, ' ', STR_PAD_RIGHT);
        $log[] = $req_id;
        $log[] = str_pad($log_id, ConstEmv::LEN_LOG_ID, ' ', STR_PAD_RIGHT);
        if (!empty($shop_code)) {
            $log[] = str_pad($shop_code, ConstEmv::LEN_SHOP_CODE, ' ', STR_PAD_RIGHT);
        } else {
            $log[] = str_pad('-', ConstEmv::LEN_SHOP_CODE, ' ', STR_PAD_RIGHT);
        }
        if (!empty($agent_id)) {
            $log[] = str_pad($agent_id, ConstEmv::LEN_AGENT_ID, ' ', STR_PAD_RIGHT);
        } else {
            $log[] = str_pad('-', ConstEmv::LEN_AGENT_ID, ' ', STR_PAD_RIGHT);
        }
        $log[] = str_pad($cls, ConstEmv::LEN_CLASS_NAME, ' ', STR_PAD_RIGHT);
        $log[] = str_pad($fnc, ConstEmv::LEN_FUNC_NAME, ' ', STR_PAD_RIGHT);
        $log[] = str_pad($line, ConstEmv::LEN_LINE_NUM, ' ', STR_PAD_RIGHT);
        $log[] = $message;

        Yii::log(implode("\t", $log), $log_level);
    }

    /**
     * ログ出力設定
     *
     * @param string $param_key response parameter name.
     * @param mixed $param_value response parameter value.
     * @return string
     */
    public static function setLogParam($param_key, $param_value)
    {
        $exclude_param = Yii::app()->params['exclude_param'];
        if (is_array($param_value)) {
            foreach ($param_value as $key => $val) {
                if (is_array($val)) {
                    $val = self::setLogParam($key, $val);
                }
                if (in_array($key, $exclude_param, TRUE)) {
                    $val = '********';
                }
                $param_value[$key] = $val;
            }
            $ret = $param_value;
        } else {
            if (in_array($param_key, $exclude_param, TRUE)) {
                $param_value = '********';
            }
            $ret = $param_value;
        }

        return $ret;
    }
}
