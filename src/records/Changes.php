<?php
/**
 * donations-free plugin for Craft CMS 3.x
 *
 * Free Braintree Donation System
 *
 * @link      https://endurant.org
 * @copyright Copyright (c) 2017 endurant
 */

namespace endurant\mailmanager\records;

use craft\db\ActiveRecord;
use craft\records\User;

/**
 * Mail Type Record
 *
 * @property integer $id
 * @property integer $templateId
 * @property integer $userId
 * @property string $oldVersion
 * @property string $newVersion
 * @property string $dateCreated
 * @property string $dateUpdated
 * @property string $uid
 */
class Changes extends ActiveRecord
{
    public $oldVersionArr;
    public $newVersionArr;
    // Public Static Methods
    // =========================================================================

    /**
     * Declares the name of the database table associated with this AR class.
     * By default this method returns the class name as the table name by calling [[Inflector::camel2id()]]
     * with prefix [[Connection::tablePrefix]]. For example if [[Connection::tablePrefix]] is `tbl_`,
     * `Customer` becomes `tbl_customer`, and `OrderItem` becomes `tbl_order_item`. You may override this method
     * if the table is not named after this convention.
     *
     * By convention, tables created by plugins should be prefixed with the plugin
     * name and an underscore.
     *
     * @return string the table name
     */
    public static function tableName()
    {
        return '{{mailmanager_changes}}';
    }

    public static function getColumns()
    {
        return ['ID', 'Template Slug', 'User', 'Date Created', 'Date Updated'];
    }

    public static function log(array $oldVersion, Template $newVersion)
    {
        $attributes = [];
        foreach ($oldVersion as $key => $value) {
            if ($value != $newVersion->$key) {
                $attributes[] = $key;
            } else {
                unset($oldVersion[$key]);
            }
        }

        $changes = new Changes();
        $changes->templateId = $newVersion->id;
        $changes->userId = \Craft::$app->user->id;
        $changes->oldVersion = json_encode($oldVersion);
        $changes->newVersion = json_encode($newVersion->getAttributes($attributes));
        $changes->save();
    }

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

    public function getTemplate()
    {
        return Template::find()->where(['id' => $this->templateId])->one();
    }

    public function getUser()
    {
        return User::find()->where(['id' => $this->userId])->one();
    }
}
