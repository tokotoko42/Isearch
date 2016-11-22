<?php

class BaseModel extends CActiveRecord
{
    public function __construct($scenario='insert') {
        parent::__construct($scenario);
        $this->attachBehaviors($this->behaviors());
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            if (isset($this->created)) {
                $this->modified = $this->created;
            } else {
                $this->created = $this->modified = date('Y-m-d H:i:s');
            }
        } else {
            $this->modified = date('Y-m-d H:i:s');
        }
        return parent::beforeSave();
    }

    public function output()
    {
        return $this->attributes;
    }
    
    /**
     * ログ出力(改善)
     *
     * @param string $log_id ログID[API NAME]-[NNN]
     * @param string $log_level [info/warning/error/trace/profile]のいずれか
     * @param string $cls クラス名
     * @param string $fnc 機能名
     * @param string $line 行番号
     * @param string $message メッセージ内容
     * @param string $shop_code ショップコード
     * @param string $agent_id エージェントID
     */
    public function setLog($log_id, $log_level, $cls, $fnc, $line, $message, $shop_code = '', $agent_id = '')
    {
        LogUtil::setLog($this->api_token, $log_id, $log_level, $this->request_id, $cls, $fnc, $line, $message, $shop_code , $agent_id );
    }
}
