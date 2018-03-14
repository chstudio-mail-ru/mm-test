<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Order list';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <h3>Фильтр</h3>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'filter-order-form']); ?>
            <?= $form->field($model, 'f_status')->dropDownList(['all' => '', 'new' => 'new', 'confirmed' => 'confirmed', 'canceled' => 'canceled', 'closed' => 'closed']); ?>
            <?= $form->field($model, 'f_user_id')->dropDownList($user_list); ?>
            <?= $form->field($model, 'f_sum_min') ?>
            <?= $form->field($model, 'f_sum_max') ?>
            <div class="form-group">
                <?= Html::submitButton('Фильтр', ['class' => 'btn btn-primary', 'name' => 'filter-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php if (Yii::$app->session->hasFlash('filterFormSubmitted')): ?>
        <div class="alert alert-success">
            Применен фильтр
        </div>
    <?php endif; ?>

    <h3>Список Orders</h3>

    <table class="table table-bordered">
        <tr>
            <th>id</th><th>status</th><th>user name</th><th>sum</th><th>date add</th><th>date change</th><th>edit link</th><th>history link</th>
        </tr>
        <? foreach($list_orders as $order):?>
            <tr>
                <td><?= $order['id'] ?></td><td><?= $order['status'] ?></td><td><?= $order['user_name'] ?></td><td><?= $order['sum'] ?></td><td><?= $order['date_add'] ?></td><td><?= $order['date_change'] ?></td>
                <td><a href="/editorder/<?= $order['id'] ?>">edit</a></td>
                <td><a href="/historyorder/<?= $order['id'] ?>">view</a></td>
            </tr>
        <? endforeach; ?>
    </table>
</div>
