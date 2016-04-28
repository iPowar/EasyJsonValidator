<?php

use Powar\JsonValidator\Validator;

/**
 * @author Mikhail Kudryashov <mikhail.kudryashov@opensoftdev.ru>
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
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
        return [
            [$json = json_encode(['key' => 1]), $rules = ['key' => Validator::TYPE_INTEGER], $hasErrors = false],
            [$json = json_encode(['key' => 1]), $rules = ['key' => Validator::TYPE_STRING], $hasErrors = true],
        ];
    }
}