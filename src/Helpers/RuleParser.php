<?php

namespace Matrixbrains\FormSync\Helpers;

class RuleParser
{
    public static function toSchema($rules, $messages = [])
    {
        $schema = ['fields' => [], 'messages' => $messages];

        foreach ($rules as $field => $ruleSet) {
            $fieldRules = is_array($ruleSet) ? $ruleSet : explode('|', $ruleSet);
            $parsed = [];

            foreach ($fieldRules as $rule) {
                if (is_string($rule)) {
                    if (str_contains($rule, ':')) {
                        [$name, $value] = explode(':', $rule, 2);
                        $parsed[$name] = $value;
                    } else {
                        $parsed[$rule] = true;
                    }
                }
            }

            $schema['fields'][$field] = $parsed;
        }

        return $schema;
    }
}
