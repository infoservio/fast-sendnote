<?php

namespace infoservio\mailmanager\models;

use craft\base\Model;
use infoservio\mailmanager\components\mailmanager\transports\Mailgun;
use infoservio\mailmanager\components\mailmanager\transports\Gmail;
use infoservio\mailmanager\components\mailmanager\transports\Postal;

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
    public $mailer = Gmail::class;
    public $from = 'support@gmail.com';
    public $firstName = 'Support';
    public $lastName = 'Support';

    // mailgun
    public $mailgunDomain;
    public $mailgunKey;

    // postal
    public $postalHost;
    public $postalServerKey;
    public $postalFrom;

    // gmail
    public $gmailEmail;
    public $gmailPassword;
    public $gmailTimeout = 10;

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
            [['mailgunKey', 'mailer', 'from', 'mailgunDomain', 'firstName', 'lastName', 'postalHost', 'postalServerKey', 'postalFrom'], 'string'],
            [['postalFrom', 'from'], 'email'],
            [['mailer', 'from', 'firstName', 'lastName'], 'required'],
            [['mailgunKey', 'mailgunDomain'], 'required', 'when' => function($model) {
                return $model->mailer == Mailgun::class;
            }],
            [['postalHost', 'postalServerKey', 'postalFrom'], 'required', 'when' => function($model) {
                return $model->mailer == Postal::class;
            }],
            [['gmailEmail', 'gmailPassword', 'gmailTimeout'], 'required', 'when' => function($model) {
                return $model->mailer == Gmail::class;
            }],
        ];
    }
}
