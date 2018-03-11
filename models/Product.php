<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\base\Model;

/**
 * Product class for products.
 */
class Product extends Model
{
    public $id;
    public $articul;
    public $name;
    public $description;
    public $price;
    public $num;
    public $f_articul;
    public $f_name;
    public $f_description;
    public $f_price_min;
    public $f_price_max;


    public static function findIdentity($id)
    {
        $query = new Query();

        $row = $query->select(['*'])
                     ->from('products')
                     ->where(['id' => $id])
                     ->one();


        return isset($row['id'])? new static($row) : null;
    }

    /**
     * add new product to DB
     * MySQL query INSERT INTO products (articul, name, description, price, num) VALUES ($articul, $name, $description, $price, $num)
     * @param  string $articul
     * @param  string $name
     * @param  string $description
     * @param  integer $price
     * @param  integer $num
     * @return static|null
     */

    public static function addProduct($articul, $name, $description, $price, $num)
    {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand()
                                    ->insert('products', [
                                        'articul' => $articul,
                                        'name' => $name,
                                        'description' => $description,
                                        'price' => $price,
                                        'num' => $num,
                                    ]);
        $command->execute();
        $id = $connection->getLastInsertID();

        $arr =  [
                    'id' => $id,
                    'articul' => $articul,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'num' => $num,
                ];
        return new static($arr);
    }

    /**
     * list all available filtered products
     * MySQL query SELECT * FROM products WHERE num>=1 ORDER BY name
     * @return array
     */
    public function listProducts()
    {
        $query = new Query();

        $query->select(['*'])
            ->from('products')
            ->where(['>=', 'num', 1]);

        if(!empty($this->f_articul))
            $query->andFilterCompare('articul', $this->f_articul);
        if(!empty($this->f_name))
            $query->andFilterCompare('name', $this->f_name);
        if(!empty($this->f_description))
            $query->andFilterCompare('description', $this->f_description, 'like');
        if(!empty($this->f_price_min))
            $query->andFilterCompare('price', '>='.$this->f_price_min);
        if(!empty($this->f_price_max))
            $query->andFilterCompare('price', '<='.$this->f_price_max);

        $rows = $query->orderBy(['id' => SORT_DESC])
            ->all();

        return $rows;
    }

    /**
     * save product to DB
     */
    public static function saveProduct($id, $articul, $name, $description, $price, $num)
    {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand()
                                    ->update('products', [
                                        'articul' => $articul,
                                        'name' => $name,
                                        'description' => $description,
                                        'price' => $price,
                                        'num' => $num,
                                    ], 'id='.$id);
        $command->execute();
    }

    /**
     * decrement products num
     */
    public function decrement()
    {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('UPDATE products SET num=num-1 WHERE id='.$this->id);
        $command->execute();
    }

    /**
     * increment products num
     */
    public function increment()
    {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('UPDATE products SET num=num+1 WHERE id='.$this->id);
        $command->execute();
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['f_articul', 'f_name', 'f_description', 'f_price_min', 'f_price_max'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'f_articul' => 'Артикул',
            'f_name' => 'Название',
            'f_description' => 'Описание содержит',
            'f_price_min' => 'Минимальная цена',
            'f_price_max' => 'Максимальная цена',
        ];
    }
}