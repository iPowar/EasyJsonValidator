
Introduction
-------------

EasyJsonValidator - simple Json validator for PHP. 
Very faster, easy to configuration.
This validator work without JsonSchema(http://json-schema.org/).

Capability
-------------
1. Simple type check, like field `name` - string
2. Range check, like filed `number` - form 1 to 10, or field `date` -> from 2019-01-01 to 2019-02-01
3. Require check, like field `id` is require
4. Nested objects check, like multidimensional array with all available validation
5. DateTime check in any date format
6. Check by pattern (preg_match), like field `email` - `/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i`
7. Check by callback, like field `email` - `function($email) { return filter_var($email, FILTER_VALIDATE_EMAIL); }`
8. Customisation error message for any rule

Installation
-------------
    composer require powar/json-validator

Usage
-----
**get any json**

    $json = json_encode(['id' => 11, 'name' => 'JohnDoe', 'balance' => null, 'date' => date(DateTime::ISO8601)]);

**create rules**

    $rules = [
        'id' => [
            Validator::KEY_TYPE => Validator::TYPE_INTEGER,
            Validator::KEY_MAX_VAL => 10,
        ],
        'name' => Validator::TYPE_STRING,
        'balance' => Validator::TYPE_NULL,
        'date' => Validator::TYPE_DATETIME,
    ];

**validate**

    $validator = new \Powar\JsonValidator\Validator();
    $validator->validate($json, $rules);

**check errors exist**

    if ($validator->hasErrors()) {
        print_r($validator->getErrors());
    }
    
    array(1) {
       'id' =>
       string(20) "must be less than 10"
     }
    

Documentation
-------------
**Make rules**

The validate method takes a second parameter with array of rules for validation

simple example: 

    $rules = ['id' => 'int']
    $validator->validate($json, $rules);

This rule will only check the incoming json for matching the _id:integer_ field.

**_Note, you can write explicitly 'int' or another type, but I recommend using Validator::TYPE_* constants.**

example with constant: 

    $rules = ['id' => Validator::TYPE_INTEGER];

**List of constants for rules:** 

| type | constant | available configuration
|--|--|--|
| string | Validator::TYPE_STRING | KEY_REQUIRE, KEY_MAX_STR, KEY_MIN_STR, KEY_PATTERN, KEY_CALLBACK, KEY_LABEL
| int | Validator::TYPE_INTEGER | KEY_REQUIRE, KEY_MAX_STR, KEY_MIN_STR, KEY_MIN_VAL, KEY_MAX_VAL,KEY_LABEL
| number | Validator::TYPE_NUMBER | KEY_REQUIRE, KEY_MAX_VAL, KEY_MIN_VAL, KEY_LABEL
| bool | Validator::TYPE_BOOLEAN | KEY_REQUIRE, KEY_BOOL_VAL, KEY_LABEL
| array | Validator::TYPE_ANY | KEY_REQUIRE, KEY_LABEL
| any | Validator::TYPE_BOOLEAN | KEY_REQUIRE, KEY_CALLBACK, KEY_LABEL
| datetime | Validator::TYPE_DATETIME | KEY_REQUIRE, KEY_MIN_VAL, KEY_MAX_VAL, KEY_FORMAT, KEY_LABEL
| null | Validator::TYPE_NULL | KEY_REQUIRE, KEY_LABEL



example with configure:

    $rules = ['id' => [
                Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                Validator::KEY_MAX_VAL => 10,
                ]
             ];

_**Note that now id contains an array where the required key is Validator :: KEY_TYPE, which must contain the type as in the simple case**_

**List of constants for configure:**

| constant | string | reqire | description
|--|--|--|--|
| Validator::KEY_TYPE | type | + | required key if you use array with configuration rule
| Validator::KEY_LABEL | label | - | for customization error message
| Validator::KEY_REQUIRE | require | - | just checks whether json contains element. Default true
| Validator::KEY_MIN_STR | min-str | - | Minimum number of characters (php `strlen`)
| Validator::KEY_MAX_STR | max-str | - | Maximum number of characters (php `strlen`)
| Validator::KEY_MIN_VAL | min-val | - | Minimum value (php `<`)
| Validator::KEY_MAX_VAL | max-val | - | Maximum value (php `>`)
| Validator::KEY_PATTERN | pattern | - | check by regexp (php `preg_match`)
| Validator::KEY_FORMAT | format | - | create date object from json, by format (php `DateTime::createFromFormat`)
| Validator::KEY_BOOL | format | - | create date object from json, by format (php `DateTime::createFromFormat`)
| Validator::KEY_CALLBACK | callback | - | check by callback (php `is_callable` -> `call`)
| Validator::KEY_RULE | rule | - | include rule

Some examples
--

You can use existence rule if you json contains included object

    $someRule = [
        'some_field' => [
            Validator::KEY_TYPE => Validator::TYPE_INTEGER,
            Validator::KEY_MAX_VAL => 10,
            Validator::KEY_MAX_STR => 2,
        ],
        'some_date' => [
            Validator::KEY_TYPE => Validator::TYPE_DATETIME,
            Validator::KEY_FORMAT => 'Y-m-d\TH:i:sP',
            Validator::KEY_REQUIRE => false,
        ]
    ];

    $rules = [
        'some_id' => Validator::TYPE_INTEGER,
        'some_clildren_element' => [
            Validator::KEY_TYPE => Validator::TYPE_ARRAY,
            Validator::KEY_RULE => $someRule,
        ]
    ];

Or you can create some small rules like this:

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

And use it for any rules:

    $rules = [
        'some_id' => $idRule,
        'some_date' => $dateRule,
    ];

***NOTE! In case when you create rule for TYPE_DATETIME, you need be careful, because KEY_MIN_VAL and KEY_MAX_VAL can work is not obvious***

For Example, you need to create check for day of birth (Over 18 years of age, you know ;) )

    $minDate = new DateTime();
    $minDate->modify('-18 year');
    $format = 'Y-m-d';

    $rule = [
        'day_of_birth' => [
            Validator::KEY_TYPE => Validator::TYPE_DATETIME,
            Validator::KEY_FORMAT => $format,
            Validator::KEY_MIN_VAL => $minDate->format($format), //<< WRONG
        ]
    ];

Should be with **Validator::KEY_MAX_VAL** :

    $minDate = new DateTime();
    $minDate->modify('-18 year');
    $format = 'Y-m-d';

    $rule = [
        'day_of_birth' => [
            Validator::KEY_TYPE => Validator::TYPE_DATETIME,
            Validator::KEY_FORMAT => $format,
            Validator::KEY_MAX_VAL => $minDate->format($format),
        ]
    ];

Callback example

     $rule = [
         'email' => [
             Validator::KEY_TYPE => Validator::TYPE_STRING,
             Validator::KEY_CALLBACK => function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
             }
         ]
     ];

Custom error message

	$someRule = [
	    'some_field' => [
	        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
	        Validator::KEY_MAX_VAL => 10,
	        Validator::KEY_MAX_STR => 2,
	        Validator::KEY_LABEL => 'validator.error.some_field',
	    ],
	];

getErrors() -> `['some_field' => 'validator.error.some_field']`

**More examples you can see in unit tests**
