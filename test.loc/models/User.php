<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }
    // Создание уникального токена
    public function setToken()
    {
        return $this->token = bin2hex(openssl_random_pseudo_bytes(8));
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
    }

    public function validateAuthKey($authKey)
    {
    }

    public function validatePassword($password)
    {
        return \yii::$app->security->validatePassword($password,$this->password);
    }

    public function getTokens()
    {
        return $this->hasMany(Tokens::className(),['user_id' => 'id']);
    }
}
