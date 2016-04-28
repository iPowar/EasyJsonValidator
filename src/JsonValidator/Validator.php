<?php

namespace Powar\JsonValidator;

/**
 * @author Kudryashov Mikhail <powardev@ya.ru>
 */
class Validator
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'int';
    const TYPE_BOOLEAN = 'bool';
    const TYPE_NUMBER = 'number';
    const TYPE_OBJECT = 'object';
    const TYPE_ARRAY = 'array';
    const TYPE_NULL = 'null';
    const TYPE_ANY = 'any';

    const KEY_TYPE = 'type';
    const KEY_REQUIRE = 'require';
    const KEY_MIN = 'min';
    const KEY_MAX = 'max';
    const KEY_PATTERN = 'pattern';

    /**
     * @var array
     */
    private $errorList = array();

    /**
     * @param string $json
     * @param array $rules
     */
    public function validate($json, array $rules)
    {
        $data = $this->parseJson($json);

        foreach ($rules as $key => $rule) {
            $this->checkRule($rule);

            if (!$this->hasErrors()) {
                $this->check($key, $rule, $data);
            }
        }
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        if (!empty($this->errorList)) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errorList;
    }

    /**
     * @param string $json
     * @return array
     */
    protected function parseJson($json)
    {
        $data = json_decode($json, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        $this->addError(json_last_error_msg());
    }

    /**
     * @param string $message
     */
    private function addError($message)
    {
        $this->errorList[] = $message;
    }

    /**
     * @param $rule
     * @return bool
     */
    private function checkRule($rule)
    {
        return true;
    }

    /**
     * @param $key
     * @param $rule
     * @param $data
     */
    private function check($key, $rule, $data)
    {
        $require = true;
        $pattern = null;
        $max = null;
        $min = null;
        $ruleKey = $rule;

        if (is_array($rule)) {
            $require = isset($rule[self::KEY_REQUIRE]) && is_bool($rule[self::KEY_REQUIRE]) ?: $rule[self::KEY_REQUIRE];
            $ruleKey = $rule[self::KEY_TYPE];
        }

        if (!array_key_exists($key, $data) && $require) {
            $this->addError($key . ' is require');

            return;
        }

        switch ($ruleKey) {
            case self::TYPE_INTEGER:
                if (!is_int($data[$key])) {
                    $this->addError($key . ' must be '. self::TYPE_INTEGER);

                    return;
                }

                if ($max !== null && $data[$key]) {

                }

                break;
            case self::TYPE_NUMBER:
                if (!is_numeric($data[$key])) {
                    $this->addError($key . ' must be '. self::TYPE_NUMBER);

                    return;
                }
                break;
            case self::TYPE_STRING:
                if (!is_string($data[$key])) {
                    $this->addError($key . ' must be '. self::TYPE_STRING);

                    return;
                }
                break;
            case self::TYPE_ARRAY:
                if (!is_array($data[$key])) {
                    $this->addError($key . ' must be '. self::TYPE_ARRAY);

                    return;
                }
                break;
            case self::TYPE_OBJECT:
                break;
            case self::TYPE_NULL:
                if (!is_null($data[$key])) {
                    $this->addError($key . ' must be '. self::TYPE_NULL);

                    return;
                }
                break;
            case self::TYPE_ANY:
                break;
            default:
                $this->addError('type '. $rule[self::KEY_TYPE] . ' not supported');

                return;
                break;
        }

    }
}
