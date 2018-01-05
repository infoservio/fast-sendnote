<?php

namespace infoservio\fastsendnote\models\forms;

use craft\base\Model;

class ContactForm extends Model
{
    public $amount;
    public $projectId;
    public $projectName;

    public function rules() 
    {
        return [
            [['projectName'], 'string', 'max' => 49, 'message' => 'Project Name cannot be more than 50 characters.'],
            [['amount'], 'integer', 'integerOnly' => true, 'min' => 1],
            [['projectId'], 'integer'],
            [['amount'], 'required']
        ];
    }
}