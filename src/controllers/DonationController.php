<?php
/**
 * donations-free plugin for Craft CMS 3.x
 *
 * Free Braintree Donation System
 *
 * @link      https://endurant.org
 * @copyright Copyright (c) 2017 endurant
 */

namespace endurant\smtpmailer\controllers;

use Braintree\ClientToken;
use endurant\smtpmailer\SmptMailer;

use Craft;
use craft\web\Controller;
use craft\helpers\ArrayHelper;
use endurant\smtpmailer\SmtpMailerAssetBundle;
use endurant\smtpmailer\models\forms\ContactForm;

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
class DonationController extends Controller
{
    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['pay', 'donate', 'success', 'error'];

    // Public Methods
    // =========================================================================

    public function beforeAction($action)
    {
        // ...set `$this->enableCsrfValidation` here based on some conditions...
        // call parent method that will check CSRF if such property is true.
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionSuccess() 
    {
        $view = $this->getView();

        $view->setTemplatesPath($this->getViewPath());
        // Include all the JS and CSS stuff
        $view->registerAssetBundle(SmtpMailerAssetBundle::class);
        return $this->renderTemplate('success', [
            'successText' => SmptMailer::$PLUGIN->getSettings()->successText,
            'baseUrl' => Craft::$app->session->get('baseUrl') ? Craft::$app->session->get('baseUrl') : '/'
        ]);
    }

    public function actionError() 
    {
        $view = $this->getView();

        $view->setTemplatesPath($this->getViewPath());
        // Include all the JS and CSS stuff
        $view->registerAssetBundle(SmtpMailerAssetBundle::class);
        return $this->renderTemplate('error', [
            'errorText' => SmptMailer::$PLUGIN->getSettings()->errorText,
            'baseUrl' => Craft::$app->session->get('baseUrl') ? Craft::$app->session->get('baseUrl') : '/'
        ]);
    }

    /**
     * Handle a request going to our plugin's actionDoSomething URL,
     * e.g.: actions/donations-free/donate/do-something
     *
     * @return mixed
     */
    public function actionPay()
    {
        $view = $this->getView();

        $view->setTemplatesPath($this->getViewPath());
        // Include all the JS and CSS stuff
        $view->registerAssetBundle(SmtpMailerAssetBundle::class);

        if ($params = Craft::$app->request->post()) {
            $baseUrl = Craft::$app->session->get('baseUrl') ? Craft::$app->session->get('baseUrl') : '/';
            $view->resolveTemplate('error');
            
            try {
                SmptMailer::$PLUGIN->donationService->donate(Craft::$app->request->post());
            } catch (DonationsPluginException $e) {
                return $this->redirect('/actions/donations-free/donation/error');
            } catch (\Exception $e) {
                return $this->redirect('/actions/donations-free/donation/error');
            } catch (\Error $e) {
                return $this->redirect('/actions/donations-free/donation/error');
            }

            $view->resolveTemplate('success');
            return $this->redirect('/actions/donations-free/donation/success');
        }

        $countries = ArrayHelper::toArray(Country::find()->all());
        $states = ArrayHelper::toArray(State::find()->all());
        $defaultCountryId = Country::DEFAULT_COUNTRY_ID;
        $amount = Craft::$app->session->get('donation')['amount'];
        $amount = ($amount) ? $amount : 50;
        $projectId = Craft::$app->session->get('donation')['projectId'];
        $projectName = Craft::$app->session->get('donation')['projectName'];

        $view->resolveTemplate('index');

        return $this->renderTemplate('index', [
            'amount' => $amount,
            'defaultCountryId' => $defaultCountryId,
            'countries' => $countries,
            'states' => $states,
            'btAuthorization' => SmptMailer::$PLUGIN->braintreeHttpClient->generateToken(),
            'projectId' => $projectId,
            'projectName' => $projectName,
            'mainColor' => SmptMailer::$PLUGIN->getSettings()->color
        ]);
    }

    /**
     * Handle a request going to our plugin's actionDoSomething URL,
     * e.g.: actions/donations-free/donate/do-something
     *
     * @return mixed
     */
    public function actionDonate()
    {
        $this->requirePostRequest();

        $donateForm = new DonateForm();
        $donateForm->setAttributes(Craft::$app->request->post());

        if (!$donateForm->validate()) {
            return $donateForm->getErrors();
        }

        Craft::$app->session->set('donation', $donateForm);
        Craft::$app->session->set('baseUrl', Craft::$app->request->baseUrl);

        return $this->redirect('/actions/donations-free/donation/pay');
    }
}
