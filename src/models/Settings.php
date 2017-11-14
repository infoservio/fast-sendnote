<?php
/**
 * donations-free plugin for Craft CMS 3.x
 *
 * Free Braintree Donation System
 *
 * @link      https://endurant.org
 * @copyright Copyright (c) 2017 endurant
 */

namespace endurant\mailmanager\models;

use endurant\donationsfree\Donationsfree;

use Craft;
use craft\base\Model;

/**
 * Donationsfree Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    endurant
 * @package   Donationsfree
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $mailer = 1;

    // mailgun
    public $mailgunKey = '';

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['btEnvironment', 'btMerchantId', 'btPublicKey', 'btPrivateKey', 'btAuthorization', 'errorText', 'successText', 'color'], 'string'],
            [['btEnvironment', 'btMerchantId', 'btPublicKey', 'btPrivateKey', 'btAuthorization', 'errorText', 'successText', 'color'], 'required'],
        ];
    }
}
