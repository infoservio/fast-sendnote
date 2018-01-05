<?php

namespace infoservio\fastsendnote\services;

use Craft;
use craft\base\Component;
use infoservio\fastsendnote\errors\DbMailManagerPluginException;
use infoservio\fastsendnote\models\Template;
use infoservio\fastsendnote\models\Log;
use infoservio\fastsendnote\records\Template as TemplateRecord;
use yii\web\BadRequestHttpException;

/**
 * Template Service
 *
 * @author    endurant
 * @package   MailManager
 * @since     1.0.0
 */
class TemplateService extends Component
{
    /**
     * @param array $post
     * @return TemplateRecord
     * @throws BadRequestHttpException
     * @throws DbMailManagerPluginException
     */
    public function create(array $post)
    {
        $model = new Template();
        $model->setAttributes($post);
        $model->userId = Craft::$app->user->id;
        if ($model->validate()) {
            $record = TemplateRecord::getBySlug($model->slug);
            $record = ($record) ? $record : new TemplateRecord();
            $record->setAttributes($model->getAttributes(), false);
            if (!$record->save()) {
                throw new DbMailManagerPluginException(
                    $record->errors,
                    json_encode($record->toArray()),
                    __METHOD__,
                    Log::TEMPLATE_LOGS
                );
            }

            return $record;
        } else {
            throw new BadRequestHttpException(json_encode($model->getErrors()));
        }
    }


    /**
     * @param TemplateRecord $record
     * @param array $post
     * @return TemplateRecord
     * @throws BadRequestHttpException
     * @throws DbMailManagerPluginException
     */
    public function update(TemplateRecord $record, array $post)
    {
        $model = new Template();
        $model->setAttributes($post, false);
        $model->userId = Craft::$app->user->id;
        if ($model->validate()) {
            $record->setAttributes($model->getAttributes(), false);
            if (!$record->update()) {
                throw new DbMailManagerPluginException(
                    $record->errors,
                    json_encode($record->toArray()),
                    __METHOD__,
                    Log::TEMPLATE_LOGS
                );
            }

            return $record;
        } else {
            throw new BadRequestHttpException(json_encode($model->getErrors()));
        }
    }

    /**
     * @param int $id
     * @return array|bool|TemplateRecord|null|\yii\db\ActiveRecord
     * @throws DbMailManagerPluginException
     */
    public function remove(int $id)
    {
        $record = TemplateRecord::getById($id, true);
        $record->isRemoved = Template::REMOVED;
        if (!$record->update()) {
            throw new DbMailManagerPluginException(
                $record->errors,
                json_encode($record->toArray()),
                __METHOD__,
                Log::TEMPLATE_LOGS
            );
        }

        return $record;
    }
}