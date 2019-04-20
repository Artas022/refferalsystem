
<?php
$this->title='Регистрация';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin()?>
<?= $form->field($model,"username");?>
<?=$form->field($model,"email");?>
<?= $form->field($model,"password")->passwordInput();?>
<?= html::submitButton('Отправить',['class' => 'btn btn-success'])?>
<?php $form = ActiveForm::end()?>
