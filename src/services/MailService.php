<?php
/**
 * donations-free plugin for Craft CMS 3.x
 *
 * Free Braintree Donation System
 *
 * @link      https://endurant.org
 * @copyright Copyright (c) 2017 endurant
 */

namespace infoservio\fastsendnote\services;

use craft\base\Component;

use infoservio\fastsendnote\components\fastsendnote\MailerFactory;
use infoservio\fastsendnote\components\fastsendnote\transports\BaseTransport;
use infoservio\fastsendnote\errors\DbMailManagerPluginException;
use infoservio\fastsendnote\FastSendNote;
use infoservio\fastsendnote\models\Log;
use infoservio\fastsendnote\models\Mail;
use infoservio\fastsendnote\records\Mail as MailRecord;
use infoservio\fastsendnote\records\Template as TemplateRecord;
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
        $this->_mailer = MailerFactory::createTransport(FastSendNote::$plugin->getSettings()->mailer);
    }

    /**
     * @param string $to
     * @param string $slug
     * @param array $params
     * @param string|null $customId
     * @param int|null $userId
     * @return mixed
     * @throws DbMailManagerPluginException
     */
    public function send(string $to, string $slug, array $params = [], string $customId = null, int $userId = null)
    {
        $template = TemplateRecord::getBySlug($slug);

        if (!$template) {
            return false;
        } else if(!$template->sendEmail) {
            return false;
        }

        $result = $this->_mailer->send($to, $template, $params);
        $res = $this->createMail($to, $template->id, $result, $customId, $userId);
        return $res;
    }

    /**
     * @param string $email
     * @param int $templateId
     * @param Object $result
     * @param string|null $customId
     * @param int|null $userId
     * @return bool
     * @throws DbMailManagerPluginException
     */
    private function createMail(string $email, int $templateId, $result, string $customId = null, int $userId = null)
    {
        $mailer = FastSendNote::$plugin->getSettings()->mailer;
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
        $model->customId = $customId;

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
            return false;
        }

        return true;
    }
}
