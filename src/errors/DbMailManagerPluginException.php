<?php

namespace infoservio\fastsendnote\errors;

use infoservio\fastsendnote\models\Log;


class DbMailManagerPluginException extends MailManagerPluginException
{
    protected $culprit = Log::DB_CULPRIT;
    
    public function __construct(array $errors, string $message, string $method, string $category) {
        parent::__construct($errors, $message, $method, $category);
    }
}