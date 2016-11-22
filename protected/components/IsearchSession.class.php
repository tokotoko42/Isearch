<?php

class IsearchSession extends CDbHttpSession
{
	public function readSession($id)
	{
		$data = parent::readSession($id);
		if (strlen($data)) {
			$data = Yii::app()->crypt->decrypt(base64_decode($data));
		}
		return $data;
	}

	public function writeSession($id, $data)
	{
		if (strlen($data)) {
			$data = base64_encode(Yii::app()->crypt->encrypt($data));
		}
		return parent::writeSession($id, $data);
	}
}
