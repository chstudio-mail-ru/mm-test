<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Add Product';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-product">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('addProductFormSubmitted')): ?>
        <div class="alert alert-success">
            Спасибо за добавление нового Product.
        </div>
    <?php else: ?>
        <p>
            Добавьте новый Product
        </p>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'add-product-form']); ?>
                    <?= $form->field($model, 'articul')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($model, 'name'); ?>
                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                    <?= $form->field($model, 'price') ?>
                    <?= $form->field($model, 'num') ?>
                    <div class="form-group">
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'add-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
