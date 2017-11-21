<?php

namespace endurant\mailmanager\components\mailmanager\transportadapters;

class PhpMailer extends Mailer
{
    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'PHP Mail';
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function defineTransport()
    {
        return [
            'class' => \Swift_MailTransport::class,
        ];
    }
}