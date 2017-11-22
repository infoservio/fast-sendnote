<?php
/**
 * Created by PhpStorm.
 * User: vlad
 * Date: 14.11.17
 * Time: 12:03
 */
namespace endurant\mailmanager\services;

use Craft;
use craft\base\Component;
use endurant\mailmanager\MailManager;
use endurant\mailmanager\records\Template;

class MailManagerService extends Component
{
    public function send(string $userEmail, string $templateSlug, array $params = null)
    {
        $template = Template::find()->where(['slug' => $templateSlug])->one();

        if (!$template) {
            throw new \Error('Template slug haven\'t found. Check if you actually have this template in mail manager.');
        }

        $subject = $template->name;
        $body = MailManager::$PLUGIN->templateParser->parse($template->template, $params);


    }
}