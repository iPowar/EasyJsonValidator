<?php
use Powar\JsonValidator\Validator;

include_once('../vendor/autoload.php');

$json = ['id' => 11, 'me' => '12', 'numeric' => null, 'date' => date(DateTime::ISO8601)];

$json = json_encode($json);
$validator = new Validator();

$rules = [
    'id' => [
        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
        Validator::KEY_MAX_VAL => 10
    ],
    'me' => Validator::TYPE_STRING,
    'numeric' => Validator::TYPE_NULL,
    'date' => Validator::TYPE_DATETIME,
];

$validator->validate($json, $rules);
if ($validator->hasErrors()) {
    var_dump($validator->getErrors());
}


var_dump($validator);

$idRule = [
        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
        Validator::KEY_MIN_STR => 1,
        Validator::KEY_MAX_STR => 11,
];

$dateRule = [
    Validator::KEY_TYPE => Validator::TYPE_DATETIME,
    Validator::KEY_FORMAT => DateTime::ISO8601,
    Validator::KEY_REQUIRE => false,
];

$rules = [
    'some_id' => $idRule,
    'some_date' => $dateRule,
];