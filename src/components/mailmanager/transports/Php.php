<?php

namespace infoservio\mailmanager\components\mailmanager\transports;

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
        // TODO: Implement send() method.
    }
}