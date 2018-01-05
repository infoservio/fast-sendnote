<?php

namespace infoservio\fastsendnote\components\fastsendnote\transports;

use craft\base\ComponentInterface;

use infoservio\fastsendnote\FastSendNote;
use infoservio\fastsendnote\models\Settings;
use infoservio\fastsendnote\records\Template;

/**
 * Php implements a PHP Mail transport adapter into Craft’s mailer.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since  3.0
 */
abstract class BaseTransport implements ComponentInterface
{
    protected $mailer;
    private $_settings;

    public abstract static function displayName(): string;

    /**
     * Mailer constructor.
     */
    public function __construct()
    {
        $this->_settings = FastSendNote::$plugin->getSettings();
    }

    /**
     * @return Settings
     */
    public function getParams(): Settings
    {
        return $this->_settings;
    }

    /**
     * @param string $to
     * @param Template $template
     * @param array $params
     * @param array $attachments
     * @return mixed
     */
    public abstract function send(string $to, Template $template, array $params = [], array $attachments = []);

}
