<?php

namespace endurant\smtpmailer\errors;
use endurant\smtpmailer\SmptMailer;

class SmtpMailerPluginException extends \Exception
{
    protected $message;
    protected $method;
    protected $errors;
    protected $culprit;

    private $_logService;

    public function __construct(array $errors, string $message, string $method, string $category)
    {
        parent::__construct();
        $this->errors = $errors;
        $this->message = $message;
        $this->method = $method;

        $this->_logService = SmptMailer::$PLUGIN->logService;
        $this->log($category);
    }
    
    public function getErrors() 
    {
        return json_encode($this->errors);
    }

    protected function log(string $category)
    {
        $this->_logService->setCategory($category);
        $this->_logService->log($this->errors, $this->message, $this->method, $this->culprit);
    }
}