<?php

use Powar\JsonValidator\Validator;

include_once('../vendor/autoload.php');

$start = microtime(true);
$startMemory = memory_get_usage();
$s = 100000;

for ($i = 0; $i < $s; $i++) {
    $json = ['id' => 11111, 'me' => '12', 'numeric' => '', 'date' => date(DateTime::ISO8601)];
    $json = json_encode($json);

    $validator = new Validator();

    $rules = [
        'id' => [
            Validator::KEY_TYPE => Validator::TYPE_INTEGER,
            Validator::KEY_MAX_VAL => 10,
            //Validator::KEY_LABEL => 'error.label'
        ],
        'me' => Validator::TYPE_STRING,
        'numeric' => Validator::TYPE_ANY,
        'date' => Validator::TYPE_DATETIME,
    ];

    $validator->validate($json, $rules);
}

echo (memory_get_usage() - $startMemory) / 1024 . ' mb' . PHP_EOL;
echo round(microtime(true) - $start, 4) . ' sec' . PHP_EOL;
echo round(memory_get_peak_usage(), 4) / 1024 . ' mb';


