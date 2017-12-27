<?php

namespace infoservio\mailmanager\records;

use craft\db\ActiveRecord;

/**
 * Mail Type Record
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $templateId
 * @property string $mailTypeId
 * @property string $email
 * @property string $method
 * @property string $customId
 * @property integer $isDelivered
 * @property integer $isOpened
 * @property integer $isDropped
 * @property string $dateCreated
 * @property string $dateUpdated
 * @property string $uid
 */
class Mail extends ActiveRecord
{
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
        return '{{mailmanager_mail}}';
    }

    public static function getByEmailIdAndMethod(string $emailId, int $method, bool $returnActiveRecordObj = false)
    {
        $obj = self::find()->where(['mailTypeId' => $emailId, 'method' => $method])->one();
        if (!$obj) {
            return false;
        }

        if ($returnActiveRecordObj) {
            return $obj;
        }

        return new self($obj);
    }

    public static function getLatestByEmail(string $email, string $slug, bool $returnActiveRecordObj = false)
    {
        $record = Template::getBySlug($slug);

        if (!$record) {
            return false;
        }

        $obj = self::find()->where(['email' => $email, 'templateId' => $record->id])->orderBy(['dateCreated' => SORT_DESC, 'isDelivered' => 1])->one();
        if (!$obj) {
            return false;
        }

        if ($returnActiveRecordObj) {
            return $obj;
        }

        return new self($obj);
    }
}
