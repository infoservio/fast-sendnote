<?php

namespace endurant\mailmanager\components\mailmanager;

use endurant\mailmanager\components\mailmanager\mailer\MailgunMailer;
use endurant\mailmanager\components\mailmanager\mailer\PhpMailer;

abstract class MailerFactoryAbstract
{

    public function create($id)
    {
        switch ($id) {
            case 1:
                return new MailgunMailer();
            case 2:
                return new PhpMailer();
            default:
                return new PhpMailer();
        }
    }
}