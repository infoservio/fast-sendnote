<?php

namespace infoservio\mailmanager\controllers\api\v1\webhooks;

use Craft;
use craft\web\Controller;

use infoservio\mailmanager\components\mailmanager\MailerFactory;
use infoservio\mailmanager\MailManager;
use infoservio\mailmanager\records\Mail as MailRecord;
use infoservio\mailmanager\models\Mail;
use yii\web\BadRequestHttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\Response;


/**
 * Webhook Controller
 *
 * @author    endurant
 * @package   Mailmanager
 * @since     1.0.0
 */
class PostalController extends Controller
{
    // Protected Properties
    // =========================================================================
    const MESSAGE_SENT = 'MessageSent';
    const MESSAGE_DROPPED = 'MessageDeliveryFailed';
    const MESSAGE_OPENED = 'MessageLinkClicked';
    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['status'];

    // Public Methods
    // =========================================================================

    /**
     * @param $action
     * @return bool
     * @throws NotAcceptableHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        if (!parent::beforeAction($action)) {
            return false;
        }

        Craft::$app->response->format = Response::FORMAT_JSON;
        return true;
    }

    /**
     * @return array
     * @throws NotAcceptableHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionStatus()
    {
        $this->requirePostRequest();
        $body = Craft::$app->getRequest()->getRawBody();

        $post = json_decode($body, true);

        if (!isset($post['payload']['message']) || !isset($post['payload']['status'])) {
            throw new BadRequestHttpException('Missed data.');
        }

        $email = $this->findEmail($post);
        if ($post['event'] == self::MESSAGE_SENT) {
            $email->isDelivered = 1;
        } else if ($post['event'] == self::MESSAGE_OPENED) {
            $email->isOpened = 1;
        } else if ($post['event'] == self::MESSAGE_DROPPED) {
            $email->isDropped = 1;
        } else {
            throw new BadRequestHttpException('Event does not exist.');
        }

        $email->save();

        return ['message' => 'Email dropped. Thanks!'];
    }

    /**
     * @return array|bool|Mail|null|\yii\db\ActiveRecord
     * @throws NotAcceptableHttpException
     */
    private function findEmail($post)
    {
        $email = MailRecord::getByEmailIdAndMethod($post['payload']['message']['message_id'], MailerFactory::POSTAL, true);
        if (!$email) {
            throw new NotAcceptableHttpException('Email not found');
        }

        return $email;
    }
}
