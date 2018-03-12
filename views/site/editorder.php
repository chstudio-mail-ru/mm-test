<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Edit Order';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-product">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('editOrderFormSubmitted')): ?>
        <div class="alert alert-success">
            Order сохранен
        </div>
    <?php endif; ?>
        <p>
            Редактировать Order
        </p>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'edit-order-form']); ?>
                    <?= $form->field($model, 'id')->hiddenInput() ?>
                    <?= $form->field($model, 'user_id')->dropDownList($user_list); ?>
                    <?= $form->field($model, 'status')->dropDownList(['confirmed'=>'confirmed','canceled'=>'canceled','closed'=>'closed','new'=>'new']); ?>
                Удалить товары
                <table class="table table-bordered">
                    <tr>
                        <th>id</th><th>name - price</th><th>remove link</th>
                    </tr>
                    <? foreach($delete_product_list as $id => $product):?>
                        <tr>
                            <td><?= $id; ?></td><td><?= $product ?></td><td><a href="/removefromorder/<?= $id ?>">remove</a></td>
                        </tr>
                    <? endforeach; ?>
                </table>
                Добавить товары
                <table class="table table-bordered">
                    <tr>
                        <th>id</th><th>name - price</th><th>add link</th>
                    </tr>
                    <? foreach($add_product_list as $id => $product):?>
                        <tr>
                            <td><?= $id; ?></td><td><?= $product ?></td><td><a href="/addtoorder/<?= $id ?>">add</a></td>
                        </tr>
                    <? endforeach; ?>
                </table>
                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
</div>
