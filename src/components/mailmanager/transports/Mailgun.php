<?php

namespace infoservio\mailmanager\components\mailmanager\transports;

use Craft;
use infoservio\mailmanager\MailManager;
use infoservio\mailmanager\records\Template;
use Mailgun\Mailgun as MailgunLibrary;

class Mailgun extends BaseTransport
{
    private $_domain;
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

    public function __construct()
    {
        parent::__construct();
        $this->mailer = MailgunLibrary::create($this->getParams()->mailgunKey);
        $this->_domain = $this->getParams()->mailgunDomain;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('mail-manager/_components/mailertransports/mailgun/settings', [
            'settings' => $this->getParams()
        ]);
    }


    /**
     * @param string $to
     * @param Template $template
     * @param array $params
     * @param array $attachments
     * @return mixed|\stdClass
     */
    public function send(string $to, Template $template, array $params = [], array $attachments = [])
    {
        $settings = $this->getParams();
        $parsedTemplate = MailManager::$PLUGIN->templateParser->parse($template->template, $params);

        # Next, instantiate a Message Builder object from the SDK.
        $messageBldr = $this->mailer->MessageBuilder();

        # Define the from address.
        $messageBldr->setFromAddress($settings->from, ['first' => $settings->firstName, 'last' => $settings->lastName]);
        # Define a to recipient.
        $messageBldr->addToRecipient($to);
        # Define the subject.
        $messageBldr->setSubject($template->subject);
        # Define the body of the message.
        $messageBldr->setHtmlBody($parsedTemplate);

        # Other Optional Parameters.
        foreach ($attachments as $attachment) {
            $messageBldr->addAttachment($attachment['path'], $attachment['name']);
        }

        # Set click tracking
        $messageBldr->setClickTracking(true);

        // Finally, send the message.
        return $this->mailer->post("{$this->_domain}/messages", $messageBldr->getMessage(), $messageBldr->getFiles());
    }
}