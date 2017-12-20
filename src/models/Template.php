<?php

namespace infoservio\mailmanager\models;

use craft\base\Model;
use infoservio\mailmanager\records\Template as TemplateRecord;

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
class Template extends Model
{
    const NOT_REMOVED = 0;
    const REMOVED = 1;
    /**
     * Some model attribute
     *
     * @var string
     */
    public $id;
    public $userId;
    public $slug;
    public $name;
    public $subject;
    public $template;
    public $isRemoved = self::NOT_REMOVED;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'userId'], 'integer'],
            ['slug', 'uniqueSlug'],
            ['slug', 'string', 'max' => 100],
            [['name', 'subject'], 'string', 'max' => 255],
            [['userId', 'slug', 'name', 'template', 'subject'], 'required']
        ];
    }

    public function uniqueSlug($attribute)
    {
        if ($record = TemplateRecord::getBySlug($this->slug)) {
            if ($record->id != $this->id) {
                $this->addError($attribute, 'The slug has already been taken.');
            }
        }

        return true;
    }
}
