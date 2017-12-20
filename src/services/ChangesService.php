<?php

namespace infoservio\mailmanager\services;

use Craft;
use craft\base\Component;

use infoservio\mailmanager\errors\DbMailManagerPluginException;
use infoservio\mailmanager\models\Changes;
use infoservio\mailmanager\models\Log;
use infoservio\mailmanager\records\Changes as ChangesRecord;
use infoservio\mailmanager\records\Template;

/**
 * Changes Service
 *
 * @author    endurant
 * @package   MailManager
 * @since     1.0.0
 */
class ChangesService extends Component
{

    /**
     * @param array $oldVersion
     * @param Template $newVersion
     * @return ChangesRecord
     * @throws DbMailManagerPluginException
     */
    public function create(array $oldVersion, Template $newVersion)
    {
        $attributes = [];
        foreach ($oldVersion as $key => $value) {
            if ($value != $newVersion->$key) {
                $attributes[] = $key;
            } else {
                unset($oldVersion[$key]);
            }
        }

        $model = new Changes();
        $model->templateId = $newVersion->id;
        $model->userId = Craft::$app->user->id;
        $model->oldVersion = json_encode($oldVersion);
        $model->newVersion = json_encode($newVersion->getAttributes($attributes));

        if ($model->validate()) {
            $record = new ChangesRecord();
            $record->setAttributes($model->getAttributes(), false);
            if (!$record->save()) {
                throw new DbMailManagerPluginException(
                    $record->errors,
                    json_encode($record->toArray()),
                    __METHOD__,
                    Log::CHANGES_LOGS
                );
            }

            return $record;
        }

        return null;
    }
}