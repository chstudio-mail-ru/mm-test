<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <h3>Фильтр</h3>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'filter-product-form']); ?>
            <?= $form->field($model, 'f_articul') ?>
            <?= $form->field($model, 'f_name') ?>
            <?= $form->field($model, 'f_description') ?>
            <?= $form->field($model, 'f_price_min') ?>
            <?= $form->field($model, 'f_price_max') ?>
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

    <h3>Список Products</h3>

    <table class="table table-bordered">
        <tr>
            <th>id</th><th>articul</th><th>name</th><th>description</th><th>price</th><th>num</th>
        </tr>
        <? foreach($list_products as $product):?>
        <tr>
            <td><?= $product['id'] ?></td><td><?= $product['articul'] ?></td><td><?= $product['name'] ?></td><td><?= $product['description'] ?></td><td><?= $product['price'] ?></td><td><?= $product['num'] ?></td>
        </tr>
        <? endforeach; ?>
    </table>
</div>
