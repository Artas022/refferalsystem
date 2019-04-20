<?php
/**
 * Created by PhpStorm.
 * User: artas
 * Date: 12.04.2019
 * Time: 21:13
 */
namespace app\models;
use yii\base\Model;
use yii\BaseYii;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

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
        return [
            // Обязательный ввод полей
            [ ['email','password','username'],'required','message' => "Обязательно должно быть заполнено!"],
            // Email должен быть формата example@mail.ru
            ['email','email', 'message' => "Неправильный формат E-mail!"],
            // Логин и почта должны быть уникальными
            ['email', 'unique', 'targetAttribute' => 'email', 'targetClass' => '\app\models\User',
                'message' => 'Email уже занят!'],
            ['username', 'unique', 'targetAttribute' => 'username', 'targetClass' => '\app\models\User',
                'message' => 'Ник уже занят!'],
        ];
    }
}