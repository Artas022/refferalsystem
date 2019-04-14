<?php
/* @var $this yii\web\View */

$this->title = 'Главная страница';
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Главная страница</h1>
        <?php if(Yii::$app->user->isGuest):?>
            <p class="lead">Желаете зарегистрироваться?</p>
            <?= yii\helpers\Html::a('Регистрация на сайте', ['/site/signup'], ['class'=>'btn btn-primary']) ?>
        <?php endif;?>
        <?php if(!Yii::$app->user->isGuest):?>
        <p class="lead">Приветствуем тебя, бравый рыбак <?php echo Yii::$app->user->identity->username;?><br>
        Не желаешь пригласить своих друзей, отправив им реферальную ссылку, нажав кнопку снизу?
        </p>
            <?= yii\helpers\Html::a('Пригласить друга', ['/site/profile'], ['class'=>'btn btn-primary']) ?>
        <?php endif;?>
        <p><br> <?php
            if($cat = \app\models\User::find()->count()  <= 0)
                echo '... ну, мы ещё только развиваемся, и у нас почти никого нет.' .'<br>' .'Стань первым!';
            else
                echo 'Наше комьюнити насчитывает '. \app\models\User::find()->count() . ' человек'
            ?>
        </p>
    </div>
    <div class="body-content">
        <div class="row">
        </div>
    </div>
</div>
