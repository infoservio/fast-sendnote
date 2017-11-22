<?php

namespace endurant\mailmanager\errors;

use endurant\mailmanager\models\Log;


class SendEmailException extends MailManagerPluginException
{
    protected $culprit = Log::DB_CULPRIT;
    
    public function __construct(array $errors, string $message, string $method, string $category) {
        parent::__construct($errors, $message, $method, $category);
    }
}