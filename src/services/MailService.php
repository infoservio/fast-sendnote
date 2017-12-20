<?php
/**
 * donations-free plugin for Craft CMS 3.x
 *
 * Free Braintree Donation System
 *
 * @link      https://endurant.org
 * @copyright Copyright (c) 2017 endurant
 */

namespace infoservio\mailmanager\services;

use craft\base\Component;

use infoservio\mailmanager\components\mailmanager\MailerFactory;
use infoservio\mailmanager\components\mailmanager\transports\BaseTransport;
use infoservio\mailmanager\MailManager;
use infoservio\mailmanager\records\Template as TemplateRecord;

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

    /**
     * @param string $to
     * @param string $slug
     * @param array $params
     * @return mixed
     */
    public function send(string $to, string $slug, array $params = [])
    {
        $template = TemplateRecord::getBySlug($slug);
        return $this->_mailer->send($to, $template, $params);
    }
}
