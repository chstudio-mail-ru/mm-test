<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * AddOrderForm is the model behind the add product form.
 */
class AddOrderForm extends Model
{
    public $id;
    public $status;
    public $user_id;
    public $products;
    public $add_products;
    public $delete_products;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['id'],'number','min'=>1],
            ['status','in','range'=>['new','confirmed','canceled','closed']],
            [['user_id'],'number','min'=>1],
            ['products', 'each', 'rule' => ['integer']],
            ['add_products', 'each', 'rule' => ['integer']],
            ['delete_products', 'each', 'rule' => ['integer']],
       ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id'  => '',
            'user_id'  => 'Пользователь',
            'status'  => 'Статус заказа',
            'delete_products'  => 'Удалить товары',
            'products'  => 'Добавить товары',
        ];
    }

    /**
     * Insert new order in to tables orders and orderrefproducts.
     * @return Order
     */
    public function add()
    {
        if ($this->validate()) {
            $order = Order::addOrder($this->user_id, $this->products);

            return $order;
        }
    }

    /**
     * load order from table orders.
     * @param  integer $id
     * @return Order
     */
    public function loadOrder($id)
    {
        $order = Order::findIdentity($id);

        $this->id = $order->id;
        $this->user_id = $order->user_id;
        $this->status = $order->status;
        $this->delete_products = Order::listProducts($order->id);
        $this->add_products = Order::listProducts(0);
        $this->products = Order::listProducts(0);

        return $this;
    }

    /**
     * update order in table orders.
     * @return Order
     */
    public function save()
    {
        if ($this->validate()) {
            $order = Order::saveOrder($this->id, $this->user_id, $this->status,  $this->delete_products, $this->add_products);

            return $order;
        }
    }
}
