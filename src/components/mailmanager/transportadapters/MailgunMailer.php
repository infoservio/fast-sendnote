<?php

namespace endurant\mailmanager\components\mailmanager\transportadapters;

use Craft;
use endurant\mailmanager\MailManager;

class MailgunMailer extends Mailer
{
// Static
    // =========================================================================
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Mailgun Mailer';
    }
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('mail-manager/_components/mailertransportadapters/mailgun/settings', [
            'settings' => MailManager::$PLUGIN->getSettings()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function defineTransport()
    {
//        $config = [
//            'class' => \Swift_SmtpTransport::class,
//            'host' => $this->host,
//            'port' => $this->port,
//            'timeout' => $this->timeout,
//        ];
//
//        if ($this->useAuthentication) {
//            $config['username'] = $this->username;
//            $config['password'] = $this->password;
//        }
//
//        if ($this->encryptionMethod) {
//            $config['encryption'] = $this->encryptionMethod;
//        }
//
//        return $config;
    }
}