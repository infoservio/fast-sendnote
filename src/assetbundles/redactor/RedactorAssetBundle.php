<?php
/**
 * donations-free plugin for Craft CMS 3.x
 *
 * Free Braintree Donation System
 *
 * @link      https://endurant.org
 * @copyright Copyright (c) 2017 endurant
 */

namespace infoservio\mailmanager\assetbundles\redactor;

use craft\web\AssetBundle;

/**
 * DonationsfreeAsset AssetBundle
 *
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 *
 * @author    infoservio
 * @package   Donationsfree
 * @since     1.0.0
 */
class RedactorAssetBundle extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@infoservio/mailmanager/assetbundles/redactor/dist';

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'redactor.js',
            'plugins/filemanager.js',
            'plugins/fontcolor.js',
            'plugins/fontfamily.js',
            'plugins/fontsize.js',
            'plugins/fullscreen.js',
            'plugins/imagemanager.js',
            'plugins/pagebreak.js',
            'plugins/source.js',
            'plugins/table.js',
            'plugins/video.js'
        ];

        $this->css = [
            'redactor.css'
        ];

        $this->publishOptions = ['forceCopy' => true];

        parent::init();
    }
}
