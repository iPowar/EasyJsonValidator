<?php

use Powar\JsonValidator\Validator;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param string $json
     * @param array $rules
     * @param bool $expectedResult
     *
     * @dataProvider hasErrorsDataProvider
     */
    public function testHasErrorsInt($json, $rules, $expectedResult)
    {
        $validator = new Validator();

        $validator->validate($json, $rules);

        $this->assertEquals($expectedResult, $validator->hasErrors());
    }

    public function hasErrorsDataProvider(): array
    {
        return [
            ////INT////
            [
                $json = json_encode(['key' => 1]),
                $rules = ['key' => Validator::TYPE_INTEGER],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MAX_VAL => 9,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MAX_VAL => 10,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MAX_VAL => 10.1,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MAX_VAL => 9.9,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MAX_VAL => -1,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MIN_VAL => 10,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MIN_VAL => 50,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MIN_STR => 3,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MIN_STR => 2,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MIN_STR => 1,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MAX_STR => 1,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MAX_STR => 2,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_MAX_STR => 3,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => '10']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 123]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                    ]
                ],
                $hasErrors = false
            ],

            //STRING//
            [
                $json = json_encode(['key' => 'some string']),
                $rules = ['key' => Validator::TYPE_STRING],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 1]),
                $rules = ['key' => Validator::TYPE_STRING],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 'some string']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_STRING,
                        Validator::KEY_MIN_STR => 1,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 'some string']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_STRING,
                        Validator::KEY_MIN_STR => 11,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 'some string']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_STRING,
                        Validator::KEY_MIN_STR => 12,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 'some string']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_STRING,
                        Validator::KEY_MAX_STR => 1,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 'some string']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_STRING,
                        Validator::KEY_MAX_STR => 11,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 'some string']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_STRING,
                        Validator::KEY_MAX_STR => 12,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 'some string']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_STRING,
                        Validator::KEY_PATTERN => '/[a-z]/',
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => '0123']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_STRING,
                        Validator::KEY_PATTERN => '/[a-z]/',
                    ]
                ],
                $hasErrors = true
            ],

            ////BOOLEAN////
            [
                $json = json_encode(['key' => true]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_BOOLEAN,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 1]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_BOOLEAN,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => true]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_BOOLEAN,
                        Validator::KEY_BOOL_VAL => false
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => true]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_BOOLEAN,
                        Validator::KEY_BOOL_VAL => true
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => true]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_BOOLEAN,
                        Validator::KEY_BOOL_VAL => 1
                    ]
                ],
                $hasErrors = true
            ],

            ///number///
            [
                $json = json_encode(['key' => 1]),
                $rules = ['key' => Validator::TYPE_NUMBER],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MAX_VAL => 9,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MAX_VAL => 10,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MAX_VAL => 10.1,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MAX_VAL => 9.9,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MAX_VAL => -1,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MIN_VAL => 10,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MIN_VAL => 50,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MIN_STR => 3,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MIN_STR => 2,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MIN_STR => 1,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MAX_STR => 1,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MAX_STR => 2,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 10]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_MAX_STR => 3,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => '10']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 123]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                    ]
                ],
                $hasErrors = false
            ],

            ////ANY////
            [
                $json = json_encode(['key' => 123]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_ANY,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 'string']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_ANY,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 123]),
                $rules = [
                    'key_2' => [
                        Validator::KEY_TYPE => Validator::TYPE_ANY,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => []]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_ANY,
                    ]
                ],
                $hasErrors = false
            ],

            ///null///
            [
                $json = json_encode(['key' => 123]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NULL,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => null]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NULL,
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key_2' => null]),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NULL,
                    ]
                ],
                $hasErrors = true
            ],

            ////datetime////
            [
                $json = json_encode(['key' => '2019-01-01']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_DATETIME,
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => '2019-01-01']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_DATETIME,
                        Validator::KEY_FORMAT => 'Y-m-d',
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => '2019-01-01']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_DATETIME,
                        Validator::KEY_FORMAT => 'Y-m-d',
                        Validator::KEY_MIN_VAL => (new \DateTime('2019-01-01'))->format('Y-m-d'),
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => '2019-01-01']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_DATETIME,
                        Validator::KEY_FORMAT => 'Y-m-d',
                        Validator::KEY_MIN_VAL => (new \DateTime('2018-01-01'))->format('Y-m-d'),
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => '2019-01-01']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_DATETIME,
                        Validator::KEY_FORMAT => 'Y-m-d',
                        Validator::KEY_MIN_VAL => (new \DateTime('2020-01-01'))->format('Y-m-d'),
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => '2019-01-01']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_DATETIME,
                        Validator::KEY_FORMAT => 'Y-m-d',
                        Validator::KEY_MAX_VAL => (new \DateTime('2019-01-01'))->format('Y-m-d'),
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => '2019-01-01']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_DATETIME,
                        Validator::KEY_FORMAT => 'Y-m-d',
                        Validator::KEY_MAX_VAL => (new \DateTime('2018-01-01'))->format('Y-m-d'),
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => '2019-01-01']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_DATETIME,
                        Validator::KEY_FORMAT => 'Y-m-d',
                        Validator::KEY_MAX_VAL => (new \DateTime('2020-01-01'))->format('Y-m-d'),
                    ]
                ],
                $hasErrors = false
            ],

            ////CALLBACK////
            [
                $json = json_encode(['key' => 'test@test.com']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_STRING,
                        Validator::KEY_CALLBACK => function($email) {
                            return filter_var($email, FILTER_VALIDATE_EMAIL);
                        },
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 'test@test.com']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_ANY,
                        Validator::KEY_CALLBACK => function($email) {
                            return filter_var($email, FILTER_VALIDATE_EMAIL);
                        },
                    ]
                ],
                $hasErrors = false
            ],
            [
                $json = json_encode(['key' => 'test@test.com']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                        Validator::KEY_CALLBACK => function($email) {
                            return filter_var($email, FILTER_VALIDATE_EMAIL);
                        },
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 'test@test.com']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NUMBER,
                        Validator::KEY_CALLBACK => function($email) {
                            return filter_var($email, FILTER_VALIDATE_EMAIL);
                        },
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 'test@test.com']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_BOOLEAN,
                        Validator::KEY_CALLBACK => function($email) {
                            return filter_var($email, FILTER_VALIDATE_EMAIL);
                        },
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 'test@test.com']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_DATETIME,
                        Validator::KEY_CALLBACK => function($email) {
                            return filter_var($email, FILTER_VALIDATE_EMAIL);
                        },
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 'test@test.com']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_NULL,
                        Validator::KEY_CALLBACK => function($email) {
                            return filter_var($email, FILTER_VALIDATE_EMAIL);
                        },
                    ]
                ],
                $hasErrors = true
            ],
            [
                $json = json_encode(['key' => 'test@test.com']),
                $rules = [
                    'key' => [
                        Validator::KEY_TYPE => Validator::TYPE_ARRAY,
                        Validator::KEY_CALLBACK => function($email) {
                            return filter_var($email, FILTER_VALIDATE_EMAIL);
                        },
                    ]
                ],
                $hasErrors = true
            ],
        ];
    }
}
