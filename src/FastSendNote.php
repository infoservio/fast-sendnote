<?php
/**
 * donations-free plugin for Craft CMS 3.x
 *
 * Free Braintree Donation System
 *
 * @link      https://endurant.org
 * @copyright Copyright (c) 2017 endurant
 */

namespace infoservio\fastsendnote;

use infoservio\fastsendnote\components\logger\Logger;
use infoservio\fastsendnote\components\fastsendnote\MailerFactory;
use infoservio\fastsendnote\components\fastsendnote\transports\BaseTransport;
use infoservio\fastsendnote\components\parser\TemplateParser;
use infoservio\fastsendnote\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\web\UrlManager;
use craft\events\PluginEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterCpNavItemsEvent;
use craft\web\twig\variables\Cp;

use infoservio\fastsendnote\services\ChangesService;
use infoservio\fastsendnote\services\LogService;
use infoservio\fastsendnote\services\MailService;
use infoservio\fastsendnote\services\TemplateService;
use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Vlad Hontar
 * @package   Billionglobalserver
 * @since     1.0.0
 *
 * @property  TemplateParser $templateParser
 * @property  ChangesService $changes
 * @property  TemplateService $template
 * @property  MailService $mail
 * @property  LogService $log
 * @property  Logger $logger
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class FastSendNote extends Plugin
{
    // Static Properties
    // =========================================================================
    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * DonationsFree::$plugin
     *
     * @var FastSendNote
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Donationsfree::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                    $this->hasCpSection = true;
                    $this->hasCpSettings = true;
                }
            }
        );

        Event::on(Cp::class, Cp::EVENT_REGISTER_CP_NAV_ITEMS, function(RegisterCpNavItemsEvent $event) {
            if (\Craft::$app->user->identity->admin) {

            }
        });

        // Register our site routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['fast-sendnote/send'] = 'fast-sendnote/fast-sendnote/send';
                $event->rules['fast-sendnote/mailgun/opened'] = 'fast-sendnote/api/v1/webhooks/mailgun/opened';
                $event->rules['fast-sendnote/mailgun/dropped'] = 'fast-sendnote/api/v1/webhooks/mailgun/dropped';
                $event->rules['fast-sendnote/mailgun/delivered'] = 'fast-sendnote/api/v1/webhooks/mailgun/delivered';
                $event->rules['fast-sendnote/postal/status'] = 'fast-sendnote/api/v1/webhooks/postal/status';
            }
        );

        // Register our CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['fast-sendnote'] = 'fast-sendnote/template/index';
                $event->rules['fast-sendnote/create'] = 'fast-sendnote/template/create';
                $event->rules['fast-sendnote/edit'] = 'fast-sendnote/template/update';
                $event->rules['fast-sendnote/view'] = 'fast-sendnote/template/view';
                $event->rules['fast-sendnote/delete'] = 'fast-sendnote/template/delete';
                $event->rules['fast-sendnote/not-found'] = 'fast-sendnote/site/not-found';
                $event->rules['fast-sendnote/changes'] = 'fast-sendnote/changes/index';
                $event->rules['fast-sendnote/changes/view'] = 'fast-sendnote/changes/view';
            }
        );


        Craft::info(
            Craft::t(
                'fast-sendnote',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }


    /**
     * @return string
     * @throws \craft\errors\MissingComponentException
     */
    protected function settingsHtml(): string
    {
        $allTransportAdapterTypes = MailerFactory::allMailerTransportTypes();
        $transportTypeOptions = [];
        $allTransportAdapters = [];

        foreach ($allTransportAdapterTypes as  $transportAdapterType) {
            /** @var string|BaseTransport $transportAdapterType */
            $allTransportAdapters[] = MailerFactory::createTransport($transportAdapterType);
            $transportTypeOptions[] = [
                'value' => $transportAdapterType,
                'label' => $transportAdapterType::displayName()
            ];

        }
        $settings = $this->getSettings();

        $adapter = MailerFactory::createTransport($settings->mailer);

        return Craft::$app->view->renderTemplate(
            'fast-sendnote/settings',
            [
                'settings' => $settings,
                'adapter' => $adapter,
                'transportTypeOptions' => $transportTypeOptions,
                'allTransportAdapters' => $allTransportAdapters
            ]
        );
    }
}