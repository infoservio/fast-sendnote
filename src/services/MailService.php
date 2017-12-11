<?php
/**
 * donations-free plugin for Craft CMS 3.x
 *
 * Free Braintree Donation System
 *
 * @link      https://endurant.org
 * @copyright Copyright (c) 2017 endurant
 */

namespace endurant\mailmanager\services;

use craft\base\Component;

use endurant\mailmanager\components\mailmanager\MailerFactory;
use endurant\mailmanager\components\mailmanager\transports\BaseTransport;
use endurant\mailmanager\MailManager;

use endurant\mailmanager\models\Template;
use endurant\mailmanager\records\Template as TemplateRecord;

/**
 * MailService Service
 *
 * @author    endurant
 * @package   Mailmanager
 * @since     1.0.0
 */
class MailService extends Component
{
    /** @var BaseTransport */
    private $_mailer;

    // Public Methods
    // =========================================================================
    public function init()
    {
        parent::init();
        $this->_mailer = MailerFactory::createTransport(MailManager::$PLUGIN->getSettings()->mailer);
    }

    public function send(string $to, string $slug, array $params = [])
    {
        $template = TemplateRecord::getBySlug($slug);
        $this->_mailer->send($to, $template, $params);
    }
}
