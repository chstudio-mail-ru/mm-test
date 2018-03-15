<script>
    function addToOrder(order_id, product_id)
    {
        $.ajax({
            url: '<?= Yii::$app->request->baseUrl. '/addtoorder' ?>',
            type: 'post',
            data: {order_id: order_id , product_id: product_id},
            success: function (data) {
                arr = JSON.parse(data);
                $('#delete_products').append('<tr id="product_row_'+arr['_id']+'">' +
                    '<td>'+arr['id']+'</td><td>'+arr['articul']+'</td><td>'+arr['name']+'</td><td>'+arr['description']+'</td><td>'+arr['price']+'</td>' +
                    '<td><a href="javascript:void(0)" onclick="removeFromOrder('+arr['order_id']+', '+arr['_id']+')">remove</a></td>' +
                    '</tr>');
                $.ajax({
                    url: '<?= Yii::$app->request->baseUrl. '/sumorder' ?>',
                    type: 'post',
                    data: {order_id: order_id},
                    success: function (sum) {
                        $('#sum_order').text(sum);
                    }
                });
            }
        });
    }
    function removeFromOrder(order_id, record_id)
    {
        $.ajax({
            url: '<?= Yii::$app->request->baseUrl. '/removefromorder' ?>',
            type: 'post',
            data: {order_id: order_id , record_id: record_id},
            success: function (data) {
                arr = JSON.parse(data);
                $('#product_row_'+arr['record_id']).remove();
                $.ajax({
                    url: '<?= Yii::$app->request->baseUrl. '/sumorder' ?>',
                    type: 'post',
                    data: {order_id: arr['order_id']},
                    success: function (sum) {
                        $('#sum_order').text(sum);
                    }
                });
            }
        });
    }
</script>
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
                <table class="table table-bordered" id="delete_products">
                    <tr>
                        <th>id</th><th>articul</th><th>name</th><th>description</th><th>price</th><th>remove link</th>
                    </tr>
                    <? $sum = 0; ?>
                    <? foreach($delete_product_list as $product):?>
                        <? $sum += $product['price']; ?>
                        <tr id="product_row_<?= $product['_id']; ?>">
                            <td><?= $product['id']; ?></td><td><?= $product['articul'] ?></td><td><?= $product['name'] ?></td><td><?= $product['description'] ?></td><td><?= $product['price'] ?></td>
                            <td><a href="javascript:void(0)" onclick="removeFromOrder(<?= $model->id ?>, <?= $product['_id']; ?>)">remove</a></td>
                        </tr>
                    <? endforeach; ?>
                </table>
                Итого заказ на сумму <b id="sum_order"><?= $sum ?></b> руб.<br /><br />
                Добавить товары
                <table class="table table-bordered">
                    <tr>
                        <th>id</th><th>articul</th><th>name</th><th>description</th><th>price</th><th>add link</th>
                    </tr>
                    <? foreach($add_product_list as $product):?>
                        <tr>
                            <td><?= $product['id']; ?></td><td><?= $product['articul'] ?></td><td><?= $product['name'] ?></td><td><?= $product['description'] ?></td><td><?= $product['price'] ?></td>
                            <td><a href="javascript:void(0)" onclick="addToOrder(<?= $model->id ?>, <?= $product['id']; ?>)">add</a></td>
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
