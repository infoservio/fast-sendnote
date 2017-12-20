<?php

namespace endurant\mailmanager\controllers;

use endurant\mailmanager\MailManager;

use Craft;
use craft\web\Controller;
use infoservio\mailmanager\models\Template;
use infoservio\mailmanager\records\Template as TemplateRecord;
use yii\web\BadRequestHttpException;

/**
 * Template Controller
 * @author    endurant
 * @package   Mailmanager
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

    /**
     * @return \yii\web\Response
     */
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

    /**
     * @return \yii\web\Response
     */
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

    /**
     * @return \yii\web\Response
     */
    public function actionCreate()
    {
        if ($post = Craft::$app->request->post())
        {
            try {
                $template = MailManager::$PLUGIN->template->create($post);
            } catch (\Exception $e) {
                return $this->renderTemplate('mail-manager/templates/create', [
                    'errors' => json_decode($e->getMessage()),
                    'template' => $post
                ]);
            }
            return $this->redirect('mail-manager/view?id=' . $template->id);
        }

        return $this->renderTemplate('mail-manager/templates/create');
    }

    /**
     * @return \yii\web\Response
     */
    public function actionUpdate()
    {
        $record = TemplateRecord::getById(Craft::$app->request->getParam('id'), true);

        if (!$record) {
            return $this->redirect('mail-manager/not-found');
        }

        if ($post = Craft::$app->request->post())
        {
            try {
                $template = MailManager::$PLUGIN->template->update($record, $post);
            } catch (\Exception $e) {
                return $this->renderTemplate('mail-manager/templates/update', [
                    'errors' => json_decode($e->getMessage()),
                    'template' => $post
                ]);
            }
            return $this->redirect('mail-manager/view?id=' . $template->id);
        }

        return $this->renderTemplate('mail-manager/templates/update', [
            'template' => $record
        ]);
    }

    /**
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionDelete()
    {
        $this->requirePostRequest();
        $post = Craft::$app->request->post();

        try {
            $template = MailManager::$PLUGIN->template->remove($post['id']);
        } catch (\Exception $e) {
            // TODO make up something
        }

        return $this->redirect('mail-manager');
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionSend()
    {
        $this->requirePostRequest();
    }
}
