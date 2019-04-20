<?php

namespace app\models;

use yii\BaseYii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
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
        return $this->hasMany(Tokens::className(),['ref_id' => 'id']);
    }

    // добавление пользователя в БД
    public function addUser($model)
    {
        $this->username = $model->username;
        $this->email = $model->email;
        $this->password = BaseYii::$app->security->generatePasswordHash($model->password);
        // генерация токена
        $this->token = bin2hex(openssl_random_pseudo_bytes(8));
        // вывод сообщения об успешной регистрации
        BaseYii::$app->session->setFlash('success', "Вы успешно зарегистрировались на сайте!");
        $this->save();
    }
}
