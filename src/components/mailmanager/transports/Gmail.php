<?php

namespace infoservio\fastsendnote\components\fastsendnote\transports;

use Craft;
use infoservio\fastsendnote\FastSendNote;
use infoservio\fastsendnote\records\Template;

class Gmail extends BaseTransport
{
    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Gmail Mail';
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('fast-sendnote/_components/mailertransports/gmail/settings', [
            'settings' => $this->getParams()
        ]);
    }

    // Public Methods
    // =========================================================================

    public function send(string $to, Template $template, array $params = [], array $attachments = [])
    {
        $settings = $this->getParams();
        $parsedTemplate = FastSendNote::$plugin->templateParser->parse($template->template, $params);

        $mailer = Craft::$app->getMailer();

        $mailer->setTransport([
            'class' => \Swift_SmtpTransport::class,
            'host' => 'smtp.gmail.com',
            'port' => 465,
            'encryption' => 'ssl',
            'username' => $settings->gmailEmail,
            'password' => $settings->gmailPassword,
            'timeout' => $settings->gmailTimeout

        ]);

        $mail = $mailer->compose()
            ->setFrom($settings->from)
            ->setTo($to)
            ->setSubject($template->subject)
            ->setHtmlBody($parsedTemplate);

        foreach ($attachments as $attachment) {
            $mail->attach($attachment['path']);
        }

        return $mail->send();

    }
}