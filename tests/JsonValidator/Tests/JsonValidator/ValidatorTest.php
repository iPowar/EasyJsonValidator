<?php

use Powar\JsonValidator\Validator;

/**
 * @author Mikhail Kudryashov <mikhail.kudryashov@opensoftdev.ru>
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $exampleDate = '01.01.2016';

    public function testValidate()
    {
        //$this->assertEquals(1,1);
    }

    /**
     * @param string $json
     * @param array $rules
     * @param bool $expectedResult
     *
     * @dataProvider hasErrorsDataProvider
     */
    public function testHasErrors($json, $rules, $expectedResult)
    {
        $validator = new Validator();

        $validator->validate($json, $rules);

        $this->assertEquals($expectedResult, $validator->hasErrors());
    }

    /**
     * @return array
     */
    public function hasErrorsDataProvider()
    {
        $date = new DateTime($this->exampleDate);

        return [
            [$json = json_encode(['key' => 1]), $rules = ['key' => Validator::TYPE_INTEGER], $hasErrors = false],
            [
                $json = json_encode(['key' => 10]),
                $rules = ['key' => [
                    Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                    Validator::KEY_MAX => 9,
                ]],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = ['key' => [
                    Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                    Validator::KEY_MAX => 10,
                ]],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = ['key' => [
                    Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                    Validator::KEY_MAX => 10.1,
                ]],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = ['key' => [
                    Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                    Validator::KEY_MAX => 9.9,
                ]],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = ['key' => [
                    Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                    Validator::KEY_MAX => -1,
                ]],
                $hasErrors = true
            ],

            [
                $json = json_encode(['key' => 10]),
                $rules = ['key' => [
                    Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                    Validator::KEY_MIN => 10,
                ]],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = ['key' => [
                    Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                    Validator::KEY_MIN => 50,
                ]],
                $hasErrors = true
            ],



//            [$json = json_encode(['key' => 1]), $rules = ['key' => Validator::TYPE_STRING], $hasErrors = true],
//            [$json = json_encode(['date' => date(DateTime::ISO8601)]), $rules = ['date' => Validator::TYPE_DATETIME], $hasErrors = false],
//            [$json = json_encode(
//                ['date' => $date->format('j-M-Y')]),
//                ['date' => [Validator::KEY_TYPE => Validator::TYPE_DATETIME, Validator::KEY_FORMAT => 'j-M-Y']],
//                $hasErrors = false],
//            [$json = json_encode(
//                ['key' => ['id' => 1]]),
//                $rules = ['key' => Validator::TYPE_ARRAY],
//                $hasErrors = false,
//            ],
//            [$json = json_encode(
//                ['key' => ['id' => 1]]),
//                $rules = ['key' => [
//                    Validator::KEY_TYPE => Validator::TYPE_ARRAY,
//                    Validator::KEY_RULE => ['id' => Validator::TYPE_INTEGER]
//                ]],
//                $hasErrors = false
//            ]
        ];
    }
}