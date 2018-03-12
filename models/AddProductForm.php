<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * AddProductForm is the model behind the add product form.
 */
class AddProductForm extends Model
{
    public $id;
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
            [['id'],'number','min'=>1],
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
            'id'  => '',
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

    /**
     * load product from table products.
     * @return Product
     */
    public function loadProduct($id)
    {
        $product = Product::findIdentity($id);

        $this->id = $product->id;
        $this->articul = $product->articul;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->num = $product->num;

        return $this;
    }

    /**
     * update product in table products.
     * @return Product
     */
    public function save()
    {
        if ($this->validate()) {
            $product = Product::saveProduct($this->id, $this->articul, $this->name, $this->description, $this->price, $this->num);

            return $product;
        }
    }
}
