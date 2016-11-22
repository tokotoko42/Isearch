<?php

class WebApiException extends ApiException
{
    public $display_message = null;

    public function toArray()
    {
        $result = parent::toArray();

        // exceptin env
        $result['exception_env'] = 'WEB';

        if ($this->display_message) {
            $result['display_message'] = $this->display_message;
        }

        return $result;
    }
}
