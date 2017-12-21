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

        return parent::beforeAction($action);
    }

    /**
     * @return array
     * @throws NotAcceptableHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionStatus()
    {
        $this->requirePostRequest();
        $post = Craft::$app->request->post();

        if (!isset($post['message']) || !isset($post['message']['message_id']) || !isset($post['status'])) {
            throw new BadRequestHttpException('Missed data.');
        }

        $email = $this->findEmail($post);
        if ($post['status'] == self::MESSAGE_SENT) {
            $email->isDelivered = 1;
        } else if ($post['status'] == self::MESSAGE_OPENED) {
            $email->isOpened = 1;
        } else if ($post['status'] == self::MESSAGE_DROPPED) {
            $email->isDropped = 1;
        } else {
            die(json_encode($post));
        }
        $email->save();

        return ['message' => 'Email dropped. Thanks!'];
    }

    /**
     * @return array|bool|Mail|null|\yii\db\ActiveRecord
     * @throws NotAcceptableHttpException
     */
    private function findEmail(array $post)
    {
        $email = MailRecord::getByEmailIdAndMethod($post['message']['message_id'], MailerFactory::POSTAL, true);
        if (!$email) {
            throw new NotAcceptableHttpException('Email not found');
        }

        return $email;
    }
}
