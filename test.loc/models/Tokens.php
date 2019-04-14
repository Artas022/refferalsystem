<?php
/**
 * Created by PhpStorm.
 * User: artas
 * Date: 13.04.2019
 * Time: 21:03
 */

namespace app\models;
use yii\BaseYii;
use yii\db\ActiveRecord;

class Tokens extends ActiveRecord
{
    public static function tableName()
    {
        return 'token';
    }

    // Функция нахождение токена
    public static function SetValue()
    {
            $ref = new Tokens();
            $owner = new User();
            // Находим в БД, кому принадлежит token, записываем его данные
            $owner = User::find()->asArray()->
            where(['token' => BaseYii::$app->request->get('token')])->one();
            // Записываем его id в БД рефералов
            $ref['user_id'] = $owner['id'];
            return $ref;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }
}