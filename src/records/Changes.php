<?php

namespace infoservio\mailmanager\records;

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

    public function getTemplate()
    {
        return Template::find()->where(['id' => $this->templateId])->one();
    }

    public function getUser()
    {
        return User::find()->where(['id' => $this->userId])->one();
    }
}
