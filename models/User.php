<?php
namespace app\models;

class User extends \sf\db\Model
{
    public static function tableName()
    {
        return 'user';
    }
}