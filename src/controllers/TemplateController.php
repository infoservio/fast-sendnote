<?php

namespace infoservio\fastsendnote\controllers;

use infoservio\fastsendnote\FastSendNote;

use Craft;
use craft\web\Controller;
use infoservio\fastsendnote\models\Template;
use infoservio\fastsendnote\records\Template as TemplateRecord;
use yii\web\BadRequestHttpException;

/**
 * Template Controller
 * @author    endurant
 * @package   Mailmanager
 * @since     1.0.0
 */
class TemplateController extends BaseController
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

    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $columns = TemplateRecord::getColumns();
        $templates = TemplateRecord::find()->where(['isRemoved' => Template::NOT_REMOVED])->orderBy('id DESC')->all();
        return $this->renderTemplate('fast-sendnote/templates/index', [
            'columns' => $columns,
            'templates' => $templates,
            'isUserHelpUs' => $this->isUserHelpUs,
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
            return $this->redirect('fast-sendnote/not-found');
        }

        return $this->renderTemplate('fast-sendnote/templates/view', [
            'template' => $template,
            'isUserHelpUs' => $this->isUserHelpUs
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
                $template = FastSendNote::$plugin->template->create($post);
            } catch (\Exception $e) {
                return $this->renderTemplate('fast-sendnote/templates/create', [
                    'errors' => json_decode($e->getMessage()),
                    'template' => $post
                ]);
            }
            return $this->redirect('fast-sendnote/view?id=' . $template->id);
        }

        return $this->renderTemplate('fast-sendnote/templates/create', [
            'isUserHelpUs' => $this->isUserHelpUs
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionUpdate()
    {
        $record = TemplateRecord::getById(Craft::$app->request->getParam('id'), true);

        if (!$record) {
            return $this->redirect('fast-sendnote/not-found');
        }

        if ($post = Craft::$app->request->post())
        {
            try {
                $template = FastSendNote::$plugin->template->update($record, $post);
            } catch (\Exception $e) {
                return $this->renderTemplate('fast-sendnote/templates/update', [
                    'errors' => json_decode($e->getMessage()),
                    'template' => $post,
                    'isUserHelpUs' => $this->isUserHelpUs
                ]);
            }
            return $this->redirect('fast-sendnote/view?id=' . $template->id);
        }

        return $this->renderTemplate('fast-sendnote/templates/update', [
            'template' => $record,
            'isUserHelpUs' => $this->isUserHelpUs
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
            $template = FastSendNote::$plugin->template->remove($post['id']);
        } catch (\Exception $e) {
            // TODO make up something
        }

        return $this->redirect('fast-sendnote');
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionSend()
    {
        $this->requirePostRequest();
    }
}
