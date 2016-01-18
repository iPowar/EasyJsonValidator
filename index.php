<?php
use Powar\JsonValidator\Validator;

include_once('Validator.php');

$json = ['id' => 11, 'me' => '12', 'numeric' => null];

$json = json_encode($json);
$validator = new Validator($json);


$rules = array(
    'id' => array(
        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
        Validator::KEY_MAX => 10
    ),
    'me' => Validator::TYPE_STRING,
    'numeric' => Validator::TYPE_NULL,
);

$validator->validate($json, $rules);
if ($validator->hasErrors()) {
    var_dump($validator->getErrors());
}


var_dump($validator);