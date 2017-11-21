<?php
/**
 * donations-free plugin for Craft CMS 3.x
 *
 * Free Braintree Donation System
 *
 * @link      https://endurant.org
 * @copyright Copyright (c) 2017 endurant
 */

namespace endurant\mailmanager;

use endurant\mailmanager\components\mailmanager\MailerFactory;
use endurant\mailmanager\components\mailmanager\MailerFactoryAbstract;
use endurant\mailmanager\components\mailmanager\MailerHelper;
use endurant\mailmanager\components\mailmanager\transportadapters\PhpMailer;
use endurant\mailmanager\components\mailmanager\transportadapters\TransportAdapterInterface;
use endurant\mailmanager\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\web\UrlManager;
use craft\events\PluginEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterCpNavItemsEvent;
use craft\web\twig\variables\Cp;

use endurant\mailmanager\records\MailType as MailTypeRecord;
use ReflectionClass;
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
 * @author    endurant
 * @package   Donationsfree
 * @since     1.0.0
 *
 * @property Plugin $plugin
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class MailManager extends Plugin
{
    // Static Properties
    // =========================================================================
    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * DonationsFree::$plugin
     *
     * @var MailManager
     */
    public static $PLUGIN;

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
        self::$PLUGIN = $this;

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
                $event->navItems['mail-manager'] = [
                    'label' => \Craft::t('mail-manager', 'Mail Manager'),
                    'url' => 'mail-manager'
                ];
            }
        });

        // Register our site routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['mail-manager'] = 'mail-manager/mail-manager/index';
            }
        );

        // Register our CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['mail-manager'] = 'mail-manager/template/index';
                $event->rules['mail-manager/create'] = 'mail-manager/template/create';
                $event->rules['mail-manager/edit'] = 'mail-manager/template/update';
                $event->rules['mail-manager/view'] = 'mail-manager/template/view';
                $event->rules['mail-manager/delete'] = 'mail-manager/template/delete';
                $event->rules['mail-manager/not-found'] = 'mail-manager/site/not-found';
            }
        );


        Craft::info(
            Craft::t(
                'mail-manager',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    public function getCpNavItem()
    {
        $item = parent::getCpNavItem();
        $item['subnav'] = [
            'foo' => ['label' => 'Foo', 'url' => 'mail-manager/foo'],
            'bar' => ['label' => 'Bar', 'url' => 'mail-manager/bar'],
            'baz' => ['label' => 'Baz', 'url' => 'mail-manager/baz'],
        ];
        return $item;
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
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        $allTransportAdapterTypes = MailerFactory::allMailerTransportTypes();
        $transportTypeOptions = [];
        $allTransportAdapters = [];

        foreach ($allTransportAdapterTypes as  $transportAdapterType) {
            /** @var string|TransportAdapterInterface $transportAdapterType */
            $allTransportAdapters[] = MailerFactory::createTransportAdapter($transportAdapterType);
            $transportTypeOptions[] = [
                'value' => $transportAdapterType,
                'label' => $transportAdapterType::displayName()
            ];

        }
        $settings = $this->getSettings();

        $adapter = MailerFactory::createTransportAdapter($settings->mailer);

        return Craft::$app->view->renderTemplate(
            'mail-manager/settings',
            [
                'settings' => $settings,
                'adapter' => $adapter,
                'transportTypeOptions' => $transportTypeOptions,
                'allTransportAdapters' => $allTransportAdapters
            ]
        );
    }
}