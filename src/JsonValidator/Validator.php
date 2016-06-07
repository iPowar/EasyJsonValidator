<?php
namespace Powar\JsonValidator;

use DateTime;

/**
 * @author Kudryashov Mikhail <powardev@ya.ru>
 */
class Validator
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'int';
    const TYPE_BOOLEAN = 'bool';
    const TYPE_NUMBER = 'number';
    const TYPE_ARRAY = 'array';
    const TYPE_NULL = 'null';
    const TYPE_ANY = 'any';
    const TYPE_DATETIME = 'datetime';

    const KEY_TYPE = 'type';
    const KEY_REQUIRE = 'require';
    const KEY_MIN = 'min';
    const KEY_MAX = 'max';
    const KEY_PATTERN = 'pattern';
    const KEY_FORMAT = 'format';
    const KEY_RULE = 'rule';

    /**
     * @var array
     */
    private $errorList = array();

    /**
     * @var string
     */
    private $dateTimeFormat = DateTime::ISO8601;

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
    protected function addError($message)
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
        $format = null;
        $includeRule = null;

        if (is_array($rule)) {
            $require = isset($rule[self::KEY_REQUIRE]) && is_bool($rule[self::KEY_REQUIRE]) ? $rule[self::KEY_REQUIRE] : $require;
            $max = !empty($rule[self::KEY_MAX]) ? $rule[self::KEY_MAX] : $max;
            $min = !empty($rule[self::KEY_MIN]) ? $rule[self::KEY_MIN] : $min;
            $ruleKey = $rule[self::KEY_TYPE];
            $format = !empty($rule[self::KEY_FORMAT]) ? $rule[self::KEY_FORMAT] : $format;
            $includeRule = !empty($rule[self::KEY_RULE]) ? $rule[self::KEY_RULE] : $includeRule;
            $pattern = !empty($rule[self::KEY_PATTERN]) ? $rule[self::KEY_PATTERN] : $pattern;
        }

        if (!array_key_exists($key, $data) && $require) {
            $this->addError($key . ' is require');

            return;
        }

        switch ($ruleKey) {
            case self::TYPE_INTEGER:
                if (!is_int($data[$key])) {
                    $this->addError($key . ' must be ' . self::TYPE_INTEGER);

                    return;
                } elseif ($max && $data[$key] > $max) {
                    $this->addError($key . ' must be less than' . $max);

                    return;
                } elseif ($min && $data[$key] < $min) {
                    $this->addError($key . ' must be more than' . $min);

                    return;
                }

                break;
            case self::TYPE_NUMBER:
                if (!is_numeric($data[$key])) {
                    $this->addError($key . ' must be ' . self::TYPE_NUMBER);

                    return;
                }

                break;
            case self::TYPE_STRING:
                if (!is_string($data[$key])) {
                    $this->addError($key . ' must be ' . self::TYPE_STRING);

                    return;
                }

                if ($pattern !== null) {
                    $result = preg_match($pattern, $key);
                    if ($result === 0) {
                        $this->addError($key . ' must match the pattern ' . $pattern);
                    } elseif ($result === false) {
                        $this->addError($pattern .' has error: ' . preg_last_error());
                    }
                }

                break;
            case self::TYPE_ARRAY:
                if (!is_array($data[$key])) {
                    $this->addError($key . ' must be ' . self::TYPE_ARRAY);

                    return;
                } elseif ($includeRule) {
                    $this->validate(json_encode($data[$key]), $includeRule);
                }
                break;
            case self::TYPE_NULL:
                if (!is_null($data[$key])) {
                    $this->addError($key . ' must be ' . self::TYPE_NULL);

                    return;
                }
                break;
            case self::TYPE_DATETIME:
                $dateTime = $data[$key];
                $format = $format ? $format : $this->dateTimeFormat;
                if (!DateTime::createFromFormat($format, $dateTime)) {
                    $this->addError($key . ' must be ' . self::TYPE_DATETIME . '(' . $format . ')');
                }
                return;
                break;
            case self::TYPE_ANY:
                break;
            default:
                $this->addError('type ' . $rule[self::KEY_TYPE] . ' not supported');

                return;
                break;
        }
    }
}
