<?php

namespace endurant\mailmanager\services;

use Craft;
use craft\base\Component;

use endurant\mailmanager\errors\DbMailManagerPluginException;
use endurant\mailmanager\models\Changes;
use endurant\mailmanager\models\Log;
use endurant\mailmanager\records\Changes as ChangesRecord;
use endurant\mailmanager\records\Template;
use yii\web\BadRequestHttpException;

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