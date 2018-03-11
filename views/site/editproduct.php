<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Edit Product';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-product">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('saveProductFormSubmitted')): ?>
        <div class="alert alert-success">
            Product сохранен
        </div>
    <?php endif; ?>
        <p>
            Редактировать Product
        </p>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'add-product-form']); ?>
                    <?= $form->field($model, 'id')->hiddenInput() ?>
                    <?= $form->field($model, 'articul') ?>
                    <?= $form->field($model, 'name') ?>
                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                    <?= $form->field($model, 'price') ?>
                    <?= $form->field($model, 'num') ?>
                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
</div>
