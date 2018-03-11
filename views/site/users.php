<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Список Users
    </p>

    <table class="table table-bordered">
        <tr>
            <th>id</th><th>name</th><th>e-mail</th>
        </tr>
        <? foreach($list_users as $user):?>
        <tr>
            <td><?= $user['id'] ?></td><td><?= $user['name'] ?></td><td><?= $user['email'] ?></td>
        </tr>
        <? endforeach; ?>
    </table>
</div>
