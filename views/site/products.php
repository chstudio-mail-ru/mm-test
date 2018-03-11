<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Список Products
    </p>

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
