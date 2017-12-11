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
use endurant\mailmanager\MailManager;

/**
 * Mail Type Record
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $slug
 * @property integer $name
 * @property string $subject
 * @property string $template
 * @property integer $isRemoved
 * @property string $dateCreated
 * @property string $dateUpdated
 * @property string $uid
 */
class Template extends ActiveRecord
{
    public $oldVersion;
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
        return '{{mailmanager_template}}';
    }

    public static function getColumns()
    {
        return ['ID', 'Name', 'Slug', 'User ID', 'Date Created', 'Date Updated'];
    }

    public static function getBySlug(string $slug, bool $returnActiveRecordObj = false)
    {
        $obj = self::find()->where(['slug' => $slug])->one();
        if (!$obj) {
            return false;
        }

        if ($returnActiveRecordObj) {
            return $obj;
        }

        return new self($obj);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert && !empty($changedAttributes)) {
            unset($changedAttributes['dateCreated']);
            unset($changedAttributes['dateUpdated']);
            MailManager::$PLUGIN->changesService->create($changedAttributes, $this);
        }
    }

    public function getChanges()
    {
        // a customer has many comments
        return Changes::find()->where(['templateId' => $this->id])->all();
    }

    public function getUser()
    {
        return User::find()->where(['id' => $this->userId])->one();
    }
}
