<?php

namespace infoservio\fastsendnote\controllers;

use Craft;
use craft\web\Controller;

use infoservio\fastsendnote\records\Changes;


/**
 * Changes Controller
 *
 * @author    endurant
 * @package   Mailmanager
 * @since     1.0.0
 */
class ChangesController extends BaseController
{
    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = [];

    public function actionIndex()
    {
        $changes = Changes::find()->orderBy('id DESC')->all();
        return $this->renderTemplate('fast-sendnote/changes/index', [
            'changes' => $changes,
            'columns' => Changes::getColumns(),
            'isUserHelpUs' => $this->isUserHelpUs
        ]);
    }

    public function actionView()
    {
        $templateChange = Changes::find()->where(['id' => Craft::$app->request->getParam('id')])->one();

        if (!$templateChange) {
            return $this->redirect('fast-sendnote/not-found');
        }

        $templateChange->oldVersionArr = json_decode($templateChange->oldVersion, true);
        $templateChange->newVersionArr = json_decode($templateChange->newVersion, true);

        return $this->renderTemplate('fast-sendnote/changes/view', [
            'templateChange' => $templateChange,
            'isUserHelpUs' => $this->isUserHelpUs
        ]);
    }
}
