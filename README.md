Introduction
-------------

EasyJsonValidator - simple Json validator for PHP

Installation
-------------


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

    $validator = new Validator();
    $validator->validate($json, $rules);

**check errors exist**

    if ($validator->hasErrors()) {
        var_dump($validator->getErrors());
    }


Documentation
-------------
**Make rules**

The validate method takes a second parameter with array of rules for validation

simple example: 

    $rules = ['id' => 'int']
    $validator->validate($json, $rules);

This rule will only check the incoming json for matching the _id:integer_ field.

**_Note, you can write explicitly 'int' or another type, but I recommend using constants._**

example with constant: 

    $rules = ['id' => Validator::TYPE_INTEGER];

**List of constants for rules:** 

 **Validator::TYPE_STRING** - 'string'; 
 can have configure: MAX_STR, MIN_STR, REQUIRE, PATTERN
   
 
 - **Validator::TYPE_INTEGER** - 'int'; 
can have configure: MAX_STR, MIN_STR, MIN_VAL, MAX_VAL, REQUIRE
   
 -  **Validator::TYPE_BOOLEAN** - 'bool'; 
can have configure: REQUIRE
   
 -  **Validator::TYPE_NUMBER** - 'number'; 
can have configure: MAX, MIN, REQUIRE
   
 -  **Validator::TYPE_ARRAY** - 'array'; 
can have configure: MIN_VAL, MAX_VAL, RULE
   
 -  **Validator::TYPE_ANY** - 'any'; 
can have configure: REQUIRE
   
 -  **Validator::TYPE_DATETIME** - 'datetime'; 
can have configure: MIN_VAL, MAX_VAL, REQUIRE, FORMAT
   
example with configure:

    $rules = ['id' => [
                Validator::KEY_TYPE => Validator::TYPE_INTEGER,
                Validator::KEY_MAX_VAL => 10
                ]
             ];

_**Note that now id contains an array where the required key is Validator :: KEY_TYPE, which must contain the type as in the simple case**_

**List of constants for configure:** 

 - Validator::KEY_TYPE - 'type'; required key if you use array with configuration rule
 - Validator::KEY_REQUIRE - 'require'; just checks whether json contains element. Default true.  
 - Validator::KEY_MIN_STR - 'min-str'; Minimum number of characters 
 - Validator::KEY_MAX_STR - 'max-str'; Maximum number of characters
 - Validator::KEY_MIN_VAL - 'min-val'; Minimum value 
 - Validator::KEY_MAX_VAL - 'max-val'; Maximum value
 - Validator::KEY_PATTERN - 'pattern'; check by regexp 
 - Validator::KEY_FORMAT - 'format'; check by format
 - Validator::KEY_RULE - 'format'; include rule


Some examples
--

You can use existence rule if you json contains included object

    $someRule = [
        'some_field' => [
            Validator::KEY_TYPE => Validator::TYPE_INTEGER,
            Validator::KEY_MAX_VAL => 10,
            Validator::KEY_MAX_STR => 2,
            Validator::KEY_PATTERN => '\d',
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
            Validator::KEY_RULE => $someRule
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


--todo
checkrule test
speed test
error code
