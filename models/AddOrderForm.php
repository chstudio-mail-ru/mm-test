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
    public $user_id;
    public $products;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['id'],'number','min'=>1],
            [['user_id'],'number','min'=>1],
            ['products', 'each', 'rule' => ['integer']],
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
            'products'  => 'Товары',
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

}
