<?php
/**
 * donations-free plugin for Craft CMS 3.x
 *
 * Free Braintree Donation System
 *
 * @link      https://endurant.org
 * @copyright Copyright (c) 2017 endurant
 */

namespace endurant\mailmanager\controllers;

use endurant\mailmanager\MailManager;

use Craft;
use craft\web\Controller;
use craft\helpers\ArrayHelper;
use endurant\mailmanager\MailManagerAssetBundle;
use endurant\mailmanager\models\forms\ContactForm;
use endurant\mailmanager\models\Template;
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
                return MailManager::$PLUGIN->mail->send($post['to'], $post['slug']);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        throw new BadRequestHttpException('Params not found.');
    }
}
