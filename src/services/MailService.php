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
use infoservio\mailmanager\errors\DbMailManagerPluginException;
use infoservio\mailmanager\MailManager;
use infoservio\mailmanager\models\Log;
use infoservio\mailmanager\models\Mail;
use infoservio\mailmanager\records\Mail as MailRecord;
use infoservio\mailmanager\records\Template as TemplateRecord;
use PhpParser\Node\Expr\Cast\Object_;

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
     * @param int|null $userId
     * @return mixed
     * @throws DbMailManagerPluginException
     */
    public function send(string $to, string $slug, array $params = [], int $userId = null)
    {
        $template = TemplateRecord::getBySlug($slug);
        $result = $this->_mailer->send($to, $template, $params);
        $res = $this->createMail($to, $template->id, $result, $userId);
        return $res;
    }

    /**
     * @param string $email
     * @param int $templateId
     * @param Object $result
     * @param int|null $userId
     * @return bool
     * @throws DbMailManagerPluginException
     */
    private function createMail(string $email, int $templateId, $result, int $userId = null)
    {
        $mailer = MailManager::$PLUGIN->getSettings()->mailer;
        $methodId = MailerFactory::TRANSPORT_TYPES_CODES[$mailer]['id'];

        if ($methodId == 1) {
            $mailTypeId = null;
        } else if ($methodId == 2) {
            $mailTypeId = $result->http_response_body->id;
        } else {
            $mailTypeId = $result->result->message_id;
        }

        $model = new Mail();
        $model->userId = $userId;
        $model->templateId = $templateId;
        $model->mailTypeId = $mailTypeId;
        $model->email = $email;
        $model->method = $methodId;

        if ($model->validate()) {
            $record = new MailRecord();
            $record->setAttributes($model->getAttributes(), false);
            if (!$record->save()) {
                throw new DbMailManagerPluginException(
                    $record->errors,
                    json_encode($record->toArray()),
                    __METHOD__,
                    Log::MAIL_LOGS
                );
            }
        } else {
            return $model->getErrors();
        }

        return true;
    }
}
