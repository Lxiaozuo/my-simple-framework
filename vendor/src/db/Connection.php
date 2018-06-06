<?php
namespace sf\db;

/**
 * 不同的数据库连接都通过该类来进行
 * Class Connection
 * @package sf\db
 */
class Connection
{
    public $host;
    public $database;
    public $dsn;
    public $username;
    public $passwd;
    public $attritubes;

    public function getDb()
    {
       return new \PDO($this->dsn,$this->username,$this->passwd,$this->attritubes);
    }
}