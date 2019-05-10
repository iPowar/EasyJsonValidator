<?php

namespace Powar\JsonValidator;

use DateTime;

/**
 * @author Kudryashov Mikhail <powardev@ya.ru>
 */
class Validator
{
    public const TYPE_STRING = 'string';
    public const TYPE_INTEGER = 'int';
    public const TYPE_BOOLEAN = 'bool';
    public const TYPE_NUMBER = 'number';
    public const TYPE_ARRAY = 'array';
    public const TYPE_ANY = 'any';
    public const TYPE_NULL = 'null';
    public const TYPE_DATETIME = 'datetime';

    public const KEY_TYPE = 'type';
    public const KEY_REQUIRE = 'require';
    public const KEY_MIN_STR = 'min-str';
    public const KEY_MIN_VAL = 'min-val';
    public const KEY_MAX_STR = 'max-str';
    public const KEY_MAX_VAL = 'max-val';
    public const KEY_CALLBACK = 'callback';
    public const KEY_LABEL = 'label';

    public const KEY_PATTERN = 'pattern';
    public const KEY_FORMAT = 'format';
    public const KEY_RULE = 'rule';
    public const KEY_ARRAY_TYPE = 'array-type';
    public const KEY_BOOL_VAL = 'bool-val';

    /** @var array */
    private $errorList = [];

    /** @var string */
    private $dateTimeFormat = DateTime::ISO8601;

    public function validate(string $json, array $rules): void
    {
        $data = $this->parseJson($json);
        if ($this->hasErrors()) {
            return;
        }

        foreach ($rules as $key => $rule) {
            $ruleValidate = $this->checkRule($rule, $key);
            if ($ruleValidate) {
                $this->check($key, $rule, $data);
            }
        }
    }

    public function hasErrors(): bool
    {
        return !empty($this->errorList) ?: false;
    }

    public function getErrors(): ?array
    {
        return $this->errorList;
    }

    protected function parseJson(string $json): ?array
    {
        $data = json_decode($json, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        $this->addError(json_last_error_msg());

        return null;
    }

    protected function addError(string $message, ?string $key = null, ?string $label = null): void
    {
        $this->errorList[$key] = $label ?? $message;
    }

    private function checkRule($rule, ?string $key): bool
    {
        if (!is_array($rule)) {
            return $this->checkRuleType($rule);
        }

        if (empty($rule[Validator::KEY_TYPE])) {
            $this->addError('key type missing for rule: ' . $key);

            return false;
        }

        if (!$this->checkRuleType($rule[Validator::KEY_TYPE])) {
            return false;
        }

        $availableOptions = $this->getRulesOptions();
        $ruleOptions = $availableOptions[$rule[Validator::KEY_TYPE]];

        foreach ($rule as $key => $value) {
            if ($key !== Validator::KEY_TYPE && !in_array($key, $ruleOptions)) {
                $this->addError('key ' . $key . ' not supported');

                return false;
            }
        }

        return true;
    }

    private function getRulesOptions(): array
    {
        return [
            self::TYPE_STRING => [
                self::KEY_REQUIRE,
                self::KEY_MIN_STR,
                self::KEY_MAX_STR,
                self::KEY_PATTERN,
                self::KEY_CALLBACK,
                self::KEY_LABEL,
            ],
            self::TYPE_DATETIME => [
                self::KEY_REQUIRE,
                self::KEY_MIN_VAL,
                self::KEY_MAX_VAL,
                self::KEY_FORMAT,
                self::KEY_LABEL,
            ],
            self::TYPE_INTEGER => [
                self::KEY_REQUIRE,
                self::KEY_MIN_VAL,
                self::KEY_MAX_VAL,
                self::KEY_MIN_STR,
                self::KEY_MAX_STR,
                self::KEY_LABEL,
            ],
            self::TYPE_NUMBER => [
                self::KEY_REQUIRE,
                self::KEY_MIN_VAL,
                self::KEY_MAX_VAL,
                self::KEY_MIN_STR,
                self::KEY_MAX_STR,
                self::KEY_LABEL,
            ],
            self::TYPE_ARRAY => [
                self::KEY_REQUIRE,
                self::KEY_MIN_VAL,
                self::KEY_MAX_VAL,
                self::KEY_RULE,
                self::KEY_ARRAY_TYPE,
                self::KEY_LABEL,
            ],
            self::TYPE_BOOLEAN => [
                self::KEY_REQUIRE,
                self::KEY_BOOL_VAL,
                self::KEY_LABEL,
            ],
            self::TYPE_ANY => [
                self::KEY_REQUIRE,
                self::KEY_CALLBACK,
                self::KEY_LABEL,
            ],
            self::TYPE_NULL => [
                self::KEY_REQUIRE,
                self::KEY_LABEL,
            ],
        ];
    }

    private function getTypes(): array
    {
        return [
            self::TYPE_STRING,
            self::TYPE_INTEGER,
            self::TYPE_BOOLEAN,
            self::TYPE_NUMBER,
            self::TYPE_ARRAY,
            self::TYPE_ANY,
            self::TYPE_NULL,
            self::TYPE_DATETIME,
        ];
    }

    /**
     * @param string $rule
     * @return bool
     */
    private function checkRuleType(string $rule): bool
    {
        if (!in_array($rule, $this->getTypes())) {
            $this->addError($rule . ' not supported');

            return false;
        }

        return true;
    }

    private function check(string $key, $rule, array $data): void
    {
        $require = true;
        $pattern = null;
        $maxStr = null;
        $minStr = null;
        $maxVal = null;
        $minVal = null;
        $ruleKey = $rule;
        $format = null;
        $includeRule = null;
        $callback = null;
        $arrayType = null;
        $label = null;
        $boolVal = null;

        if (is_array($rule)) {
            $require = isset($rule[self::KEY_REQUIRE]) && is_bool($rule[self::KEY_REQUIRE]) ? $rule[self::KEY_REQUIRE] : $require;
            $maxStr = $rule[self::KEY_MAX_STR] ?? null;
            $minStr = $rule[self::KEY_MIN_STR] ?? null;
            $maxVal = $rule[self::KEY_MAX_VAL] ?? null;
            $minVal = $rule[self::KEY_MIN_VAL] ?? null;
            $ruleKey = $rule[self::KEY_TYPE] ?? null;
            $format = $rule[self::KEY_FORMAT] ?? null;
            $includeRule = $rule[self::KEY_RULE] ?? null;
            $pattern = $rule[self::KEY_PATTERN] ?? null;
            /** @var \Closure $callback */
            $callback = $rule[self::KEY_CALLBACK] ?? null;
            $arrayType = $rule[self::KEY_ARRAY_TYPE] ?? null;
            $label = $rule[self::KEY_LABEL] ?? null;
            $boolVal = $rule[self::KEY_BOOL_VAL] ?? null;
        }

        if (!array_key_exists($key, $data) && $require) {
            $this->addError('is require', $key, $label);

            return;
        } elseif (!array_key_exists($key, $data) && !$require) {
            return;
        }

        switch ($ruleKey) {
            case self::TYPE_INTEGER:
                if (!is_int($data[$key])) {
                    $this->addError('must be ' . self::TYPE_INTEGER, $key, $label);

                    return;
                }

                break;
            case self::TYPE_NUMBER:
                if (!is_numeric($data[$key])) {
                    $this->addError('must be ' . self::TYPE_NUMBER, $key, $label);

                    return;
                }

                break;
            case self::TYPE_STRING:
                if (!is_string($data[$key])) {
                    $this->addError('must be ' . self::TYPE_STRING, $key, $label);

                    return;
                }

                break;
            case self::TYPE_BOOLEAN:
                if (!is_bool($data[$key])) {
                    $this->addError('must be ' . self::TYPE_BOOLEAN, $key, $label);

                    return;
                }

                if (isset($boolVal) && !is_bool($boolVal)) {
                    $this->addError('rule ' . self::TYPE_BOOLEAN . ' must be with KEY_BOOL_VAL', $key, $label);

                    return;
                }

                if (isset($boolVal) && $data[$key] !== $boolVal) {
                    $this->addError('rule must be ' . $boolVal, $key, $label);

                    return;
                }

                break;
            case self::TYPE_ARRAY:
                if (!is_array($data[$key])) {
                    $this->addError('must be ' . self::TYPE_ARRAY, $key, $label);

                    return;
                } elseif ($includeRule) {
                    foreach ($data[$key] as $element) {
                        $this->validate(json_encode($element), $includeRule);
                    }
                } elseif ($arrayType) {
                    foreach ($data[$key] as $element) {
                        $this->validate(json_encode([$key => $element]), [$key => [self::KEY_TYPE => $arrayType]]);
                    }
                }

                break;
            case self::TYPE_ANY:
                if (is_null($data[$key])) {
                    $this->addError('must be not null', $key, $label);

                    return;
                }

                break;
            case self::TYPE_NULL:
                if (!is_null($data[$key])) {
                    $this->addError('must be null', $key, $label);

                    return;
                }

                break;
            case self::TYPE_DATETIME:
                $dateTime = $data[$key];
                $format = $format ? $format : $this->dateTimeFormat;
                if (!DateTime::createFromFormat($format, $dateTime)) {
                    $this->addError('must be ' . self::TYPE_DATETIME . '(' . $format . ')', $key, $label);

                    return;
                }

                break;
            default:
                $this->addError('type ' . $rule[self::KEY_TYPE] . ' not supported');

                return;
        }

        if ($maxStr && strlen($data[$key]) > $maxStr) {
            $this->addError('must be less than ' . $maxStr . ' symbols', $key, $label);

            return;
        }

        if ($minStr && strlen($data[$key]) < $minStr) {
            $this->addError('must be more than ' . $minStr . ' symbols', $key, $label);

            return;
        }

        if ($minVal && $data[$key] < $minVal) {
            $this->addError($key, 'must be more than ' . $minVal, $label);

            return;
        }

        if ($maxVal && $data[$key] > $maxVal) {
            $this->addError('must be less than ' . $maxVal, $key, $label);

            return;
        }

        if ($pattern !== null) {
            $result = preg_match($pattern, $data[$key]);
            if ($result === 0) {
                $this->addError('must match the pattern ' . $pattern, $key, $label);

                return;
            } elseif ($result === false) {
                $this->addError($pattern . ' has error: ' . preg_last_error(), $key, $label);

                return;
            }
        }

        if ($callback && is_callable($callback)) {
            if (!$callback->call($this, $data[$key])) {
                $this->addError('has error by callback', $key, $label);

                return;
            }
        }
    }
}
