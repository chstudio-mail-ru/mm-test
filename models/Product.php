<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * Product class for products.
 */
class Product extends \yii\base\Object
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
                     ->where(['id' => $id])
                     ->one();

        
        return isset($row['id'])? new static($row) : null;
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
}