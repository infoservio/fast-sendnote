<?php

namespace infoservio\mailmanager\components\mailmanager\transports;

use Craft;
use craft\mail\transportadapters\Sendmail;
use infoservio\mailmanager\MailManager;
use infoservio\mailmanager\records\Template;

class Php extends BaseTransport
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

    public function send(string $to, Template $template, array $params = [], array $attachments = [])
    {
        $settings = $this->getParams();
        $parsedTemplate = MailManager::$PLUGIN->templateParser->parse($template->template, $params);

        $mail = Craft::$app->mailer->compose()
            ->setFrom($settings->from)
            ->setTo($to)
            ->setSubject($template->subject)
            ->setHtmlBody($parsedTemplate);

        foreach ($attachments as $attachment) {
            $mail->attach($attachment['path']);
        }

        try {
            $mail->send();
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return true;

    }
}