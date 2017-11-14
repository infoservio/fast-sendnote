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

use endurant\mailmanager\components\mailmanager\MailerFactory;
use endurant\mailmanager\MailManager;

use Craft;
use craft\base\Component;

use endurant\donationsfree\models\Customer;
use endurant\donationsfree\models\Address;
use endurant\donationsfree\models\Card;
use endurant\donationsfree\models\Transaction;

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
    public $mailer;
    // Public Methods
    // =========================================================================
    public function init()
    {
        parent::init();
        $mailerFactory = new MailerFactory();
        $this->mailer = $mailerFactory->create(MailManager::$PLUGIN->getSettings()->mailer);
    }

    public function send(array $params)
    {

    }
}
