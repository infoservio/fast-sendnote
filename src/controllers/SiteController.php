<?php

namespace infoservio\fastsendnote\controllers;

use craft\web\Controller;

/**
 * Site Controller
 * @author    endurant
 * @package   Mailmanager
 * @since     1.0.0
 */
class SiteController extends Controller
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

    public function actionNotFound()
    {
        return $this->renderTemplate('fast-sendnote/layouts/not-found');
    }
}
