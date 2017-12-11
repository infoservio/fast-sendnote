<?php
/**
 * @link      https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   https://craftcms.com/license
 */

namespace endurant\mailmanager\components\mailmanager\transports;

use craft\base\SavableComponent;
use endurant\mailmanager\MailManager;
use endurant\mailmanager\models\Settings;
use endurant\mailmanager\records\Template;

/**
 * Php implements a PHP Mail transport adapter into Craft’s mailer.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since  3.0
 */
abstract class BaseTransport
{
    protected $mailer;
    private $_settings;

    public abstract static function displayName(): string;

    /**
     * Mailer constructor.
     */
    public function __construct()
    {
        $this->_settings = MailManager::$PLUGIN->getSettings();
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
