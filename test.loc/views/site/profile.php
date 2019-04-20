<?php
$this->title = 'Личный кабинет';
?>

<h2>Личный кабинет</h2>
<h4>Пользователь: <strong><?=$user['username']?></strong> </h4>
<h4>Ваш почтовый индекс: <strong><?=$user['email']?></strong> </h4>
<div class="alert alert-info" role="alert"><a href="
<?=\yii\helpers\Url::toRoute(['site/signup','token' => $user['token']],true); ?>">
        Ваша реферальная ссылка
    </a>
    <br>Скопируйте её (ПКМ->Копировать адрес ссылки) и отправьте кому-нибудь.
</div>

<p class="lead">Таблица ваших рефералов:</p>
<table class="table">
    <thead>
    <tr>
        <th>Никнейм реферала</th>
        <th>Email реферала</th>
    </tr>
    </thead>
    <tbody>
    <?php
    // получаем всех рефералов пользователя
foreach ($referals as $referal)
{
    // Находим id, username, email рефералов
    echo '
<tr>
    <td>' . $referal->username . '</td>
    <td>' . $referal->email . '</td>
</tr>';
}
?>
</tbody>

</table>


