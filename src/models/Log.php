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
class Log extends Model
{
    // Public Properties
    // =========================================================================
    const SEND_EMAIL = 'send-email';
    const CHANGES_LOGS = 'fastsendnote-changes-logs';
    const TEMPLATE_LOGS = 'fastsendnote-template-logs';
    const MAIL_LOGS = 'fastsendnote-mail-logs';

    const MAIL_MANAGER_CULPRIT = ['id' => 1, 'name' => 'fast-sendnote'];
    const DB_CULPRIT = ['id' => 2, 'name' => 'db'];
    /**
     * Some model attribute
     *
     * @var string
     */
    public $id;
    public $pid;
    public $culprit;
    public $category;
    public $method;
    public $errors;
    public $message;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'culprit'], 'integer'],
            [['method', 'errors', 'message', 'category'], 'string'],
            [['pid', 'method', 'errors', 'message'], 'required']
        ];
    }
}
