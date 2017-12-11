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
use endurant\mailmanager\records\Changes;
use endurant\mailmanager\records\Template;

/**
 * Changes Controller
 *
 * @author    endurant
 * @package   Mailmanager
 * @since     1.0.0
 */
class ChangesController extends Controller
{
    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = [];

    // Public Methods
    // =========================================================================

    public function beforeAction($action)
    {
        // ...set `$this->enableCsrfValidation` here based on some conditions...
        // call parent method that will check CSRF if such property is true.
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $changes = Changes::find()->orderBy('id DESC')->all();
        return $this->renderTemplate('mail-manager/changes/index', [
            'changes' => $changes,
            'columns' => Changes::getColumns()
        ]);
    }

    public function actionView()
    {
        $templateChange = Changes::find()->where(['id' => Craft::$app->request->getParam('id')])->one();

        if (!$templateChange) {
            return $this->redirect('mail-manager/not-found');
        }

        $templateChange->oldVersionArr = json_decode($templateChange->oldVersion, true);
        $templateChange->newVersionArr = json_decode($templateChange->newVersion, true);

        return $this->renderTemplate('mail-manager/changes/view', [
            'templateChange' => $templateChange
        ]);
    }
}
