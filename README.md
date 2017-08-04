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
            Validator::KEY_MAX => 10
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

    $rules = ['id' => Validator::TYPE_INTEGER]

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
                Validator::KEY_MAX => 10
                ]
             ]

Обратите внимание, теперь id содержит массив, где обязательным ключём является Validator::KEY_TYPE, который должен содержать тип как и в простом случае

**Доступные константы для настройки:** 

 - Validator::KEY_TYPE - 'type'; Обязательный ключ, если используется
   массив настроек правила 
 - Validator::KEY_REQUIRE - 'require'; По умолчанию true 
 - Validator::KEY_MIN - 'min'; минимальное значение поля
 - Validator::KEY_MAX - 'max'; максимальное значение поля
 - Validator::KEY_PATTERN - 'pattern'; проверка поля по шаблону
 - Validator::KEY_FORMAT - 'format'; проверка поля по формату
 - Validator::KEY_RULE - 'format'; проверка поля по формату

--todo
checkrule test
validate by pattern
readmy
speed test
error code
