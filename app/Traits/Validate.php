<?php

namespace app\Traits;

trait Validate
{
    private array $errors = [];
    private array $valids = [];

    private function getErrors(): array
    {
        return $this->errors;
    }

    private function getValids(bool $parsed = false): array
    {
        if ($parsed) {
            $valids = [];

            foreach ($this->valids as $key => $value) {
                $field = $this->fieldParser($key, true);
                $valids[$field] = $value;
            }

            return $valids;
        }

        return $this->valids;
    }

    private function addError(string $key, string $error): void
    {
        // check if key exists
        if (!isset($this->errors[$key])) {
            $this->errors[$key] = [];
        }

        // add error to key
        $this->errors[$key][] = $error;
    }

    private function addValid(string $key, $value): void
    {
        $this->valids[$key] = $value;
    }

    public function validate(): bool
    {
        // get rules from model
        if (!method_exists($this, 'rules')) {
            return true;
        }

        $rules = $this->rules();

        // check if rules is an array
        if (!is_array($rules)) {
            return true;
        }

        // split rules by pipe if needed
        foreach ($rules as $key => $rule) {
            if (is_string($rule) && strpos($rule, '|') !== false) {
                $rules[$key] = explode('|', $rule);
            }
        }

        // check rules 
        foreach ($rules as $field => $rulesOfField) {
            foreach ($rulesOfField as $rule) {
                $this->checkRule($field, $rule);
            }
        }

        return empty($this->errors);
    }

    private function checkRule(string $field, string $rule): void
    {
        // check if rule has a colon
        if (strpos($rule, ':') !== false) {
            $rule = explode(':', $rule);
            $ruleName = $rule[0];
            $ruleValue = $rule[1];
        } else {
            $ruleName = $rule;
            $ruleValue = null;
        }

        // check if rule exists
        if (!method_exists($this, $ruleName)) {
            return;
        }

        // check rule
        $this->$ruleName($field, $ruleValue);
    }

    // rules
    private function required(string $field): void
    {
        if (!isset($this->$field) || is_null($this->getValue($field)) || empty($this->getValue($field))) {
            $this->addError($field, 'Campo obrigatório');
        } else {
            $this->addValid($field, $this->getValue($field));
        }
    }

    private function max(string $field, string $max): void
    {
        if (isset($this->$field) && strlen($this->getValue($field)) > $max) {
            $this->addError($field, "Máximo de $max caracteres");
        } else {
            $this->addValid($field, $this->getValue($field));
        }
    }

    private function min(string $field, string $min): void
    {
        if (isset($this->$field) && strlen($this->getValue($field)) < $min) {
            $this->addError($field, "Mínimo de $min caracteres");
        } else {
            $this->addValid($field, $this->getValue($field));
        }
    }

    private function numeric(string $field): void
    {
        if (!is_numeric($this->$field)) {
            $this->addError($field, 'Campo tem que ser numérico');
        } else {
            $this->addValid($field, $this->$field);
        }
    }
}
