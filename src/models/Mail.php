<?php

namespace infoservio\fastsendnote\models;

use craft\base\Model;

/**
 * Card Model
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
class Mail extends Model
{
    /**
     * Some model attribute
     *
     * @var string
     */
    public $id;
    public $userId;
    public $templateId;
    public $mailTypeId;
    public $email;
    public $method;
    public $customId;
    public $isDelivered = 0;
    public $isOpened = 0;
    public $isDropped = 0;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'userId', 'templateId', 'isDelivered', 'isOpened', 'isDropped'], 'integer'],
            ['email', 'email'],
            [['templateId', 'mailTypeId', 'email'], 'required']
        ];
    }
}
