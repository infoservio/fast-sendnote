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
use endurant\mailmanager\models\Template;
use endurant\mailmanager\records\Template as TemplateRecord;

/**
 * Donate Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    endurant
 * @package   Donationsfree
 * @since     1.0.0
 */
class TemplateController extends Controller
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
        $columns = TemplateRecord::getColumns();
        $templates = TemplateRecord::find()->where(['isRemoved' => Template::NOT_REMOVED])->orderBy('id DESC')->all();
        return $this->renderTemplate('mail-manager/templates/index', [
            'columns' => $columns,
            'templates' => $templates,
            'buttons' => ['edit', 'delete']
        ]);
    }

    public function actionView()
    {
        $template = TemplateRecord::find()->where(['id' => Craft::$app->request->getParam('id')])->one();

        if (!$template) {
            return $this->redirect('mail-manager/not-found');
        }

        return $this->renderTemplate('mail-manager/templates/view', [
            'template' => $template
        ]);
    }

    public function actionCreate()
    {
        if ($post = Craft::$app->request->post())
        {
            $template = MailManager::$PLUGIN->template->create($post);
            return $this->redirect('mail-manager/view?id=' . $template->id);
        }

        return $this->renderTemplate('mail-manager/templates/create');
    }

    public function actionUpdate()
    {
        $templateRecord = TemplateRecord::find()->where(['id' => Craft::$app->request->getParam('id')])->one();

        if (!$templateRecord) {
            return $this->redirect('mail-manager/not-found');
        }

        if ($post = Craft::$app->request->post())
        {
            $res = MailManager::$PLUGIN->templateService->update($templateRecord, $post);

            if ($res['success']) {
                return $this->redirect('mail-manager/view?id=' . $res['template']->id);
            } else {
                return $this->renderTemplate('mail-manager/templates/update', [
                    'errors' => $res['errors'],
                    'template' => $post
                ]);
            }
        }

        return $this->renderTemplate('mail-manager/templates/update', [
            'template' => $templateRecord
        ]);
    }

    public function actionDelete()
    {
        $this->requirePostRequest();
        $post = Craft::$app->request->post();
        $res = MailManager::$PLUGIN->templateService->remove($post['id']);
        return $this->redirect('mail-manager');
    }

    public function actionSend()
    {
        $this->requirePostRequest();
    }
}
