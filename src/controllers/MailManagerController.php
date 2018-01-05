<?php

namespace infoservio\fastsendnote\controllers;

use infoservio\fastsendnote\FastSendNote;

use Craft;
use craft\web\Controller;
use infoservio\fastsendnote\MailManagerAssetBundle;
use yii\web\BadRequestHttpException;

/**
 * MailManager Controller
 *
 * @author    endurant
 * @package   Mailmanager
 * @since     1.0.0
 */
class MailManagerController extends Controller
{
    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['send'];

    // Public Methods
    // =========================================================================

    public function beforeAction($action)
    {
        // ...set `$this->enableCsrfValidation` here based on some conditions...
        // call parent method that will check CSRF if such property is true.
        $this->enableCsrfValidation = false;
        Craft::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSend()
    {
        $this->requirePostRequest();
        $post = Craft::$app->request->post();

        if (isset($post['to']) && isset($post['slug'])) {
            try {
                return FastSendNote::$plugin->mail->send($post['to'], $post['slug']);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        throw new BadRequestHttpException('Params not found.');
    }
}
