<?php
/**
 * Created by PhpStorm.
 * User: artas
 * Date: 12.04.2019
 * Time: 21:13
 */

namespace app\models;

use yii\db\ActiveRecord;

class SignupForm extends ActiveRecord
{

    // указано название БД
    public static function tableName()
    {
        return 'user';
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'E-mail',
            'password' => 'Пароль',
        ];
    }

    public function rules()
    {
        return[
           [ ['email','password','username'],'required','message' => "Обязательно должно быть заполнено!"],
           ['email','email', 'message' => "Неправильный формат E-mail!"],
            ['password','string','min' => 6, 'tooShort' => "Пароль должен быть не менее 6-ти символов!"],
            ['username', 'unique', 'targetAttribute' =>'username'],
            ['email', 'unique', 'targetAttribute' =>'email'],
        ];
    }
}