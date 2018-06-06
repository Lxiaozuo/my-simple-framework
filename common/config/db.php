<?php
return [
    'class' => '\sf\db\Connection',
    'dsn'   => 'mysql:host=localhost;dbname=simpleFram',
    'username' => 'root',
    'passwd' => 'root',
    'options' => [
        // PDO::ATTR_EMULATE_PREPARES 提取的时候将数值转换为字符串
        // PDO::ATTR_EMULATE_PREPARES 启用或禁用预处理语句的模拟
        \PDO::ATTR_EMULATE_PREPARES => false,
        \PDO::ATTR_STRINGIFY_FETCHES => false
    ]

];
