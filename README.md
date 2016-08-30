Introduction
-------------

EasyJsonValidator - простой валидатор Json для PHP

Installation
-------------


Usage
-----
//get any json
$json = json_encode(['id' => 11, 'name' => 'JohnDoe', 'balance' => null, 'date' => date(DateTime::ISO8601)]);

//create rules
$rules = [
    'id' => [
        Validator::KEY_TYPE => Validator::TYPE_INTEGER,
        Validator::KEY_MAX => 10
    ],
    'name' => Validator::TYPE_STRING,
    'balance' => Validator::TYPE_NULL,
    'date' => Validator::TYPE_DATETIME,
];

//validate
$validator = new Validator();
$validator->validate($json, $rules);

//check errors exist
if ($validator->hasErrors()) {
//get errors array if exist
    var_dump($validator->getErrors());
}


Documentation
-------------
Make rules
Метод validate принимает вторым параметром массив правил для валидирования
Простой пример: 
$rules = ['id' => 'int']
это правило будет проверять входящий json только на соответствие поля id - integer. 
Обратите внимание, Вы можете писать явно 'int' или другой тип, но я рекомендую использовать константы, это обезопасит ваш код, если с обновлением библиотеки, что то изменится

Простой пример с константой : 
$rules = ['id' => Validator::TYPE_INTEGER]

Перечень констант: 
Validator::TYPE_STRING - 'string';
Поддерживается MAX MIN REQUIRE PATTERN


Validator::TYPE_INTEGER - 'int';
Поддерживается MAX MIN REQUIRE

Validator::TYPE_BOOLEAN - 'bool';
Поддерживается REQUIRE

Validator::TYPE_NUMBER - 'number';
Поддерживается MAX MIN REQUIRE

Validator::TYPE_ARRAY - 'array';
Поддерживается MAX MIN RULE

Validator::TYPE_ANY - 'any';
Поддерживается REQUIRE

Validator::TYPE_DATETIME - 'datetime';
Поддерживается MAX MIN REQUIRE FORMAT

Пример с настройкой:
$rules = ['id' => [
            Validator::KEY_TYPE => Validator::TYPE_INTEGER,
            Validator::KEY_MAX => 10
            ]
         ]
Обратите внимание, теперь id содержит массив, где обязательным ключём является Validator::KEY_TYPE, который должен содержать тип как и в простом случае

Доступные константы для настройки: 
Validator::KEY_TYPE - 'type'; Обязательный ключ, если используется массив настроек правила
Validator::KEY_REQUIRE - 'require'; По умолчанию true
Validator::KEY_MIN - 'min'; минимальное значение поля
Validator::KEY_MAX - 'max'; максимальное значение поля
Validator::KEY_PATTERN - 'pattern'; проверка поля по шаблону
Validator::KEY_FORMAT - 'format'; проверка поля по формату
Validator::KEY_RULE - 'format'; проверка поля по формату


--todo
checkrule test
validate by pattern
readmy
speed test
error code
https://habrahabr.ru/post/308298/