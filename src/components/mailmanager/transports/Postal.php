<?php

namespace endurant\mailmanager\components\mailmanager\transports;

use Craft;
use endurant\mailmanager\MailManager;
use endurant\mailmanager\records\Template;
use Postal as PostalLibrary;

class Postal extends BaseTransport
{
    private $_domain;
    // Static
    // =========================================================================
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Postal Mailer';
    }
    // Public Methods
    // =========================================================================

    public function __construct()
    {
        parent::__construct();
        $this->mailer = new PostalLibrary\Client($this->getParams()->postalHost, $this->getParams()->postalServerKey);
        $this->_domain = $this->getParams()->mailgunDomain;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('mail-manager/_components/mailertransports/postal/settings', [
            'settings' => $this->getParams()
        ]);
    }


    /**
     * @param string $to
     * @param Template $template
     * @param array $params
     * @param array $attachments
     * @return mixed|\Postal\SendResult
     */
    public function send(string $to, Template $template, array $params = [], array $attachments = [])
    {
        $settings = $this->getParams();
        $parsedTemplate = MailManager::$PLUGIN->templateParser->parse($template->template, $params);

        // Create a new message
        $message = new PostalLibrary\SendMessage($this->mailer);

        // Add recipient
        $message->to($to);

        // Specify who the message should be from. This must be from a verified domain
        // on your mail server.
        $message->from($settings->postalFrom);

        // Set the subject
        $message->subject($template->subject);

        // Set the content for the e-mail
        $message->htmlBody($parsedTemplate);

        // Add any custom headers
        $message->header('X-PHP-Test', 'value');

        // Attach any files
        foreach ($attachments as $attachment) {
            $message->attach($attachment['path'], $attachment['contentType'], $attachment['name']);
        }

        // Send the message and get the result
        return $message->send();
    }
}