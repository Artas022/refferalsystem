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

    public function getUser()
    {
        return $this->hasOne(User::className(),['user_id' => 'id']);
    }

    // вывод сообщения от кого ссылка,
    // если токен существует и привязан к пользователю
    public static function checkToken()
    {
        if(!Tokens::isReferal())
            return null;
            $owner = User::find()->select(['id','username','email'])->where(['token' => BaseYii::$app->request->get('token')])->asArray()->one();
            BaseYii::$app->session->setFlash('success', "Вы перешли по реферальной ссылке! Вас пригласил:" . '<br>' .
                'Пользователь: ' . $owner['username'] . '<br>' .
                'E-mail: ' . $owner['email'] . '<br>');
    }

    public static function setRef($user)
    {
        if(!Tokens::isReferal())
            return null;
        $ref = User::find()->select(['id'])->where(['token' => $user['token']])->asArray()->one();
        // находим владельца токена
        $owner = User::find()->select(['id'])->where(['token' => BaseYii::$app->request->get('token')])->asArray()->one();
        // связь и запись в БД
        $referal = new Tokens();
        $referal->ref_id=$ref['id'];
        $referal->user_id=$owner['id'];
        $referal->save();
    }

    // проверка наличия реферала по токену сессии
    public static function isReferal()
    {
        if(User::find()->where(['token' => BaseYii::$app->request->get('token')])->one())
            return true;
        else
            return false;
    }
}