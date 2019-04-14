
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
?>
<h2>Страница регистрации:</h2>
<?php if(Yii::$app->session->hasFlash("success"))?>
<?php if(Yii::$app->session->hasFlash("error"))?>

<?php
if (User::find()->where(['token' => Yii::$app->request->get('token')])->one()):?>
    <div class="alert alert-success" role="alert">
        <strong><?php    // Проверяем наличие токена реферальной ссылки
    {
    $token = Yii::$app->request->get('token');
    $owner = new User();
    $owner = User::find()->asArray()->where(['token' => $token])->one();
    echo 'Вы перешли по реферальной ссылке от пользователя: ' . '<br>' .
    'Пользователь: '. $owner['username'] . '<br>' .
        'Почта: ' . $owner['email'];
    }
    ?></strong>
    </div>
<?php endif;?>


<?php $form = ActiveForm::begin()?>
<?= $form->field($model,"username");?>
<?=$form->field($model,"email");?>
<?= $form->field($model,"password")->passwordInput();?>
<?= html::submitButton('Отправить',['class' => 'btn btn-success'])?>
<?php $form = ActiveForm::end()?>
