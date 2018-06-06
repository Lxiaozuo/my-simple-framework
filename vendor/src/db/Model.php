<?php

namespace sf\db;

class Model implements ModelInterface
{
    public static $pdo;

    /**
     * db单例
     */
    public static function getDb()
    {
        if (empty(static::$pdo)) {

            $host = 'localhost';
            $database = 'simpleFram';
            $username = 'root';
            $password = 'root';
            $options = [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_STRINGIFY_FETCHES => false
            ];
            // PDO::ATTR_EMULATE_PREPARES 提取的时候将数值转换为字符串
            // PDO::ATTR_EMULATE_PREPARES 启用或禁用预处理语句的模拟
            static::$pdo = new \PDO("mysql:host=$host;dbname=$database;", $username, $password, $options);
            static::$pdo->exec("set names 'UTF8'");
        }

        return static::$pdo;
    }


    public static function primaryKey()
    {
        return ['id'];
    }

    public static function tableName()
    {
        return static::tableName();
    }

    /**
     * 根据condition找到相应的信息
     * @param $condition
     */
    public static function findOne($condition = null)
    {
        list($where, $params) = self::bindWhere($condition);
        $sql = "select * from " . static::tableName() . $where;

        $stmt = static::getDb()->prepare($sql);
        $res = $stmt->execute($params);

        if ($res) {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            self::arrToObject($row);
        }
        return null;
    }

    public static function findAll($condition=null)
    {
        $models = [];
        list($where, $params) = self::bindWhere($condition);
        $sql = "select * from " . static::tableName() . $where;

        $stmt = static::getDb()->prepare($sql);
        $res = $stmt->execute($params);

        if ($res) {
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (!empty($rows)) {
               foreach ($rows as $key=>$row){
                   $models[$key] = self::arrToObject($row);
               }
               return $models;
            }
        }
        return null;
    }

    public static function updateAll($condition, $attributes)
    {
        $keys = [];
        $values = [];
        list($where, $params) = self::bindWhere($condition);

        foreach ($attributes as $key=>$attribute){
            array_push($keys, " $key = ? ");
            array_push($values, $attribute);
        }

        $params = array_merge($values, $params);

        $setAttritubes = implode(' , ', $keys);

        $sql = "update " . static::tableName() . " set " . $setAttritubes . $where;

        $ret = self::executeCmd($sql,$params);
        return $ret;
    }

    /**
     * bind where condition
     * @param $condition
     * @return array
     */
    public static function bindWhere($condition)
    {
        $params = [];
        $where = '';
        if (!empty($condition)) {
            $where .= " where ";
            $keys = [];
            foreach ($condition as $key => $val) {
                array_push($keys, " $key = ? ");
                array_push($params, $val);
            }
            $where .= implode(' and ', $keys);
        }

        return [$where, $params];
    }

    public function insert()
    {
        $keys = [];
        $params = [];
        $sql = "insert into " . static::tableName();

        foreach ($this as $key=>$val){
            array_push($keys, $key);
            array_push($params, $val);
        }
        // 占位?号
        $holders = array_fill(0,count($keys), '?');

        $sql .= " ( " . implode(' , ',$keys) . " ) values (" . implode(' , ',$holders). ")";
        $stmt = static::getDb()->prepare($sql);

        $ret = $stmt->execute($params);
        // 并设置id的值
        $primaryKeys = static::primaryKey();
        foreach ($primaryKeys as $key=>$primaryKey){
            $this->$primaryKey = (int)static::getDb()->lastInsertId($primaryKey);
        }

        return $ret;
    }

    /**
     * array to object
     * @param $row
     * @return null|Model
     */
    public static function arrToObject($row)
    {
        if (!empty($row)) {
            $model = new static();
            foreach ($row as $k => $v) {
                $model->$k = $v;
            }
            return $model;
        }
        return null;
    }

    public static function executeCmd($sql, $params)
    {
        $stmt = static::getDb()->prepare($sql);
        $ret = $stmt->execute($params);
        return $ret;
    }
}