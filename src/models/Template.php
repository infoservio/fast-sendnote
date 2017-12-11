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
    public $isRemoved;

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
            ['slug', 'unique'],
            [['slug', 'name', 'template', 'subject'], 'string'],
            [['userId', 'slug', 'name', 'template', 'subject'], 'required']
        ];
    }
}
