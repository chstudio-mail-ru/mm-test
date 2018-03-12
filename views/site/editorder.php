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
                    <?php /* <?= $form->field($model, 'delete_products')->dropDownList($delete_product_list, ['multiple'=>'multiple', 'style' => 'height:200px;']); ?> */ ?>
                    <?php /* <?= $form->field($model, 'add_products')->dropDownList($add_product_list, ['multiple'=>'multiple', 'style' => 'height:300px;']); ?> */ ?>
                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
</div>
