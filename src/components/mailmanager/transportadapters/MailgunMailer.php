<?php

namespace endurant\mailmanager\components\mailmanager\transportadapters;

use Craft;
use endurant\mailmanager\MailManager;
use Mailgun\Mailgun;

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

//    public function send(string $to, string $subject, string $body, string $emailType, array $attachments = [], string $to_name = '') : bool
//    {
//        if (!$from) {
//            $from = $this->from;
//        }
//
//        $messageParams = [
//            'from' => $from,//$this->name ? $this->name . '<' . $from . '>' : $from,
//            'to' => $to_name ? $to_name . '<' . $to . '>' : $to,
//            'subject' => $subject,
//            'html' => $body
//        ];
//        $postFiles = $attachments ? ['attachment' => $attachments] : [];
//
//        if (Yii::$app->params['allowDelivery']) {
//
//            $mg = new Mailgun($this->key);
//            $domain = $this->domain;
//            try {
//                $res = $mg->sendMessage($domain, $messageParams, $postFiles);
//            } catch (ErrorException $e) {
//                return $e;
//            }
//
//            /**
//             * We log all email messages
//             */
//            Yii::$app->maillog->record([
//                'emailId' => $res->http_response_body->id,
//                'emailParams' => ['emailType' => $emailType, 'emailTo' => $messageParams['to']]
//            ],
//                $res->http_response_code == 200 ? Logger::LEVEL_INFO : Logger::LEVEL_ERROR,
//                __METHOD__
//            );
//
//            return $res;
//    }

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