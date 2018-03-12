<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Add Order';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-product">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('addOrderFormSubmitted')): ?>
        <div class="alert alert-success">
            Спасибо за добавление нового Order
        </div>
    <?php else: ?>
        <p>
            Добавьте новый Order
        </p>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'add-order-form']); ?>
                    <?= $form->field($model, 'user_id')->dropDownList($user_list); ?>
                    <?= $form->field($model, 'products')->dropDownList($product_list, ['multiple'=>'multiple', 'style' => 'height:500px;']); ?>
                    <div class="form-group">
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'add-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
