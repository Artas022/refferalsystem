<?php

namespace app\controllers;

use app\models\Tokens;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
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
                'only' => ['logout','signup'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
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
        //if (!Yii::$app->user->isGuest)
        //    return $this->goHome();
        // создаём объект формы регистрации
        $model = new SignupForm();
        // проверка, является ли ссылка реферальной
        Tokens::checkToken();
        // берём данные из POST
        if($model->load(Yii::$app->request->post()))
        {
            // проверяем на валидность
            if($model->validate())
            {
                $user = new User();
                // сохраняем данные в БД
                $user->addUser($model);
                // Если токен есть и привязан к существующему пользователю
                // записать в БД
                Tokens::setRef($user);
                return $this->goHome();
            }
        }
        return $this->render("signup", compact('model'));
    }

    // Профиль / Личный кабинет пользователя
    public function actionProfile()
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        // найдём пользователя
        $user = User::find()->asArray()->
        where(['id'=> Yii::$app->user->getId()])->one();

        $referals = User::find()->select(['username','email'])->
        joinWith('tokens')->
        where(["user_id" => $user['id']])->
        all();

        return $this->render('profile',compact('user','referals'));
    }

}
