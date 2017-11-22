<?php

namespace endurant\mailmanager\components\parser;

use Craft;
use craft\base\Component;

class TemplateParser extends Component implements Parser
{
    const TEMPLATE_PARAM_START = '{{';
    const TEMPLATE_PARAM_END = '}}';

    public function parse(string $template, array $params = null)
    {
        if (!$params) {
            return $template;
        }

        foreach ($params as $key => $value)
        {
            $template = str_replace(self::TEMPLATE_PARAM_START . $key . self::TEMPLATE_PARAM_END, $value, $template);
        }

        return $template;
    }
}