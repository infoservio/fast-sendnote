<?php

namespace endurant\mailmanager\services;

use Craft;
use craft\base\Component;

use endurant\mailmanager\records\Template;

class TemplateService extends Component
{
    public function create(array $post)
    {
        $template = new Template();
        $template->userId = Craft::$app->user->id;
        $template->setAttributes($post);
        if ($template->validate() && $template->save()) {
            return ['success' => true, 'template' => $template];
        } else {
            return ['success' => false, 'errors' => $template->errors];
        }
    }

    public function update(Template $template, array $post)
    {
        $template->userId = Craft::$app->user->id;
        $template->setAttributes($post);
        if ($template->validate() && $template->save()) {
            return ['success' => true, 'template' => $template];
        } else {
            return ['success' => false, 'errors' => $template->errors];
        }
    }

    public function remove(int $id)
    {
        $template = Template::find()->where(['id' => $id])->one();
        $template->isRemoved = Template::REMOVED;
        $template->update();
    }
}