<?php

namespace endurant\mailmanager\services;

use Craft;
use craft\base\Component;

use endurant\mailmanager\records\Changes;
use endurant\mailmanager\records\Template;

class ChangesService extends Component
{
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

        $changes = new Changes();
        $changes->templateId = $newVersion->id;
        $changes->userId = Craft::$app->user->id;
        $changes->oldVersion = json_encode($oldVersion);
        $changes->newVersion = json_encode($newVersion->getAttributes($attributes));
        $changes->save();
    }
}