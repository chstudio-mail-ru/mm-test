<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'History Order '.$order_id;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <h3>Операции над заказом <?= $order_id ?></h3>

    <table class="table table-bordered">
        <tr>
            <th>date</th><th>operation</th>
        </tr>
        <? foreach($list_operations as $operation):?>
            <tr>
                <td><?= $operation['date_add'] ?></td><td><?= $operation['operation'] ?></td>
            </tr>
        <? endforeach; ?>
    </table>
</div>
