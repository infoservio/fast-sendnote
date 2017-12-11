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
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    endurant
 * @package   Donationsfree
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
