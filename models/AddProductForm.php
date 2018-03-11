<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ProductForm is the model behind the add product form.
 */
class AddProductForm extends Model
{
    public $articul;
    public $name;
    public $description;
    public $price;
    public $num;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['articul', 'name', 'description', 'price', 'num'], 'required'],
            [['price'],'number','min'=>1],
            [['num'],'number','min'=>1],
       ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'articul'  => 'Артикул',
            'name'  => 'Название',
            'description'  => 'Описание',
            'price'  => 'Цена',
            'num'  => 'Количество',
        ];
    }

    /**
     * Insert new product in to table products.
     * @return Product
     */
    public function add()
    {
        if ($this->validate()) {
            $product = Product::addProduct($this->articul, $this->name, $this->description, $this->price, $this->num);

            return $product;
        }
    }
}
