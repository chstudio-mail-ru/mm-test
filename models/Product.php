<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * Product class for products.
 */
class Product extends \yii\db\ActiveRecord
{
    public $id;
    public $articul;
    public $name;
    public $description;
    public $price;
    public $num;

    public static function findIdentity($id)
    {
        $query = new Query();

        $row = $query->select(['*'])
                     ->from('products')
                     ->where(['id' => intval($id)])
                     ->one();

        
        return isset($row['id'])? new static($row) : null;
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * find products by user name
     * MySQL query SELECT * FROM products WHERE name=$name
     * @param string $name
     * @return array
     */
    public static function findProductsByName($name)
    {
        $query = new Query();

        $rows = $query->select(['*'])
            ->from('products')
            ->where(['name' => $name])
            ->all();

        return $rows;
    }

    /**
     * @inheritdoc
     * take product $id
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * list products
     * MySQL query SELECT * FROM products WHERE num>=1 ORDER BY name
     * @return array
     */
    public function listProducts()
    {
        $query = new Query();

        $rows = $query->select(['*'])
            ->from('products')
            ->where(['>=', 'num', 1])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $rows;
    }

    /**
     * save product to DB
     */
    public function saveProduct()
    {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand()
                                    ->update('products', [
                                        'articul' => $this->articul,
                                        'name' => $this->name,
                                        'description' => $this->description,
                                        'price' => $this->price,
                                        'num' => $this->num,
                                    ], 'id='.$this->id);
        $command->execute();
    }

    /**
     * decrement products num
     */
    public function decrement()
    {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('UPDATE products SET num=num-1 WHERE id='.intval($this->id));
        $command->execute();
    }


}