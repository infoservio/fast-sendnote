<?php

namespace infoservio\mailmanager\models;

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
class Changes extends Model
{
    /**
     * Some model attribute
     *
     * @var string
     */
    public $id;
    public $userId;
    public $templateId;
    public $oldVersion;
    public $newVersion;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'userId', 'templateId'], 'integer'],
            [['oldVersion', 'newVersion'], 'string'],
            [['userId', 'templateId', 'oldVersion', 'newVersion'], 'required']
        ];
    }
}
