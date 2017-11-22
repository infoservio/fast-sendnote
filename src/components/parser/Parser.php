<?php

namespace endurant\mailmanager\components\parser;

interface Parser
{
    public function parse(string $str, array $params = null);
}