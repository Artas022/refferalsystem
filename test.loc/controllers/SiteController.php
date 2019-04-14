<?php

namespace app\controllers;

use app\models\Tokens;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;

class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    // Основная страница
    public function actionIndex()
    {
        return $this->render('index');
    }

    // Авторизация
    public function actionLogin()
    {
        // редирект на главную в случае, если user - гость
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    // Выйти из профиля
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    // Регистрация
    public function actionSignup()
    {
        $model = new SignupForm();
        if($model->load(Yii::$app->request->post()))
        {
            if($model->validate())
            {
                // провалидированные данные переписываем в модель User
                $user = new User();
                $user->username = $model['username'];
                $user->password = Yii::$app->security->generatePasswordHash($model->password);
                $user->email = $model['email'];
                // Задаём нашему User уникальный токен
                $user->setToken();
                if($user->save())
                {
                    // После удачного сохранения данных, проверяем,
                    // был ли пользователь зарегестрирован по реферальной ссылке
                    if(User::find()->
                    where(['token' => Yii::$app->request->get('token')])->one())
                    {
                        // Если токен существует - связать пользователей
                        $ref = Tokens::SetValue();
                        $temp = User::find()->select('id')->
                        where(['id' => $user['id']])->one();
                        $ref['ref_id'] = $temp['id'];
                        $ref->save();
                    }
                    Yii::$app->session->setFlash("success",
                        'Регистрация прошла успешно! Вы были авторизованы в системе сайта.');
                    Yii::$app->user->login($user);
                    return $this->goHome();
                }
            }else{
                Yii::$app->session->setFlash("error",'Ошибка отправления данных!');
            }
        }
        return $this->render("signup",compact('model'));
    }

    // Профиль / Личный кабинет пользователя
    public function actionProfile()
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        // найдём пользователя
        $user = User::find()->asArray()->where(['id'=> Yii::$app->user->getId()])->one();
        //$user = User::find()->select(['username','email'])->asArray()->where(['id'=> Yii::$app->user->getId()])->with('token','id');
        return $this->render('profile',compact('user'));
    }

}
