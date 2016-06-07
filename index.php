<?php
use Powar\JsonValidator\Validator;

include_once('vendor/autoload.php');

$json = ['id' => 11, 'me' => '12', 'numeric' => null, 'date' => date(DateTime::ISO8601)];

$json = json_encode($json);
$validator = new Validator();

$rules = [
    'id' => [
        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
        Validator::KEY_MAX => 10
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