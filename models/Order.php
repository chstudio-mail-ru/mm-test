<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * Order class for orders.
 */
class Order extends \yii\db\ActiveRecord
{
    public $id;
    public $status;
    public $user_id;
    public $date_add;
    public $date_change;
    public $products;

    public static function findIdentity($id)
    {
        $query = new Query();

        $row = $query->select(['*'])
                     ->from('orders')
                     ->where(['id' => $id])
                     ->one();

        return isset($row['id'])? new static($row) : null;
    }

    /**
     * list orders
     * MySQL query SELECT * FROM orders ORDER BY date_add DESC
     * @return array
     */
    public static function listOrders()
    {
        $query = new Query();

        $rows = $query->select(['*'])
            ->from('orders')
            ->orderBy(['date_add' => SORT_DESC])
            ->all();

        return $rows;
    }

    /**
     * find orders by user name
     * MySQL query SELECT * FROM orders LEFT JOIN users ON orders.user_id=user.id WHERE users.name=$name ORDER BY date_add DESC
     * @param string $name
     * @return array
     */
    public static function findOrders($name)
    {
        $query = new Query();

        $rows = $query->select(['orders.*'])
            ->from('orders')
            ->leftJoin('users', 'orders.user_id=user.id')
            ->where(['users.name' => $name])
            ->orderBy(['orders.date_add' => SORT_DESC])
            ->all();

        return $rows;
    }

    /**
     * @inheritdoc
     * take order $id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * add new order to DB
     * MySQL query INSERT INTO orders (user_id) VALUES ($user_id)
     * @param  integer $user_id
     * @return static|null
     */
    public static function addOrder($user_id, $products)
    {
        $connection = \Yii::$app->db;
        $t = date("Y-m-d H:i:s", time());

        $command = $connection->createCommand()
                                    ->insert('orders', [
                                        'user_id' => $user_id,
                                        'date_add' => $t,
                                    ]);
        $command->execute();
        $id = $connection->getLastInsertID();

        self::changeStatus($id, 'new');
        foreach($products as $product_id)
        {
            self::addProduct($id, $product_id);
        }

        $arr =  [
                    'id' => $id,
                    'status' => 'new',
                    'user_id' => $user_id,
                    'date_add' => $t,
                    'date_change' => $t,
                ];
        return new static($arr);
    }

    /**
     * add product to order
     * MySQL query INSERT INTO orderrefproducts (order_id, product_id) VALUES ($this->id, $product_id)
     * @param integer $id
     * @param integer $product_id
     * @return boolean
     */
    public static function addProduct($id, $product_id)
    {
        $connection = \Yii::$app->db;
        $t = date("Y-m-d H:i:s", time());

        $command = $connection->createCommand()
            ->insert('orderrefproducts', [
                'order_id' => $id,
                'product_id' => $product_id,
            ]);
        $result = $command->execute();

        if($result > 0)
        {
            $command = $connection->createCommand()
                ->update('orders', [
                    'date_change' => $t,
                ], 'id=' . $id);
            $command->execute();
        }

        return $result > 0;
    }

    /**
     * delete product from order
     * MySQL query DELETE FROM orderrefproducts WHERE order_id=$this->id AND product_id=$product_id)
     * @param  integer $product_id
     * @return boolean
     */
    public function deleteProduct($product_id)
    {
        $connection = \Yii::$app->db;
        $t = date("Y-m-d H:i:s", time());

        $command = $connection->createCommand()
            ->delete('orderrefproducts', [
                'order_id' => $this->id,
                'product_id' => $product_id,
            ]);
        $result = $command->execute();

        if($result > 0)
        {
            $command = $connection->createCommand()
                ->update('orders', [
                    'date_change' => $t,
                ], 'id=' . $this->id);
            $command->execute();
        }

        return $result > 0;
    }

    /**
     * list products ids of order
     * MySQL query SELECT product_id FROM orderrefproducts WHERE order_id=$this->id
     * @return array
     */
    public function listProducts()
    {
        $query = new Query();

        $rows = $query->select(['product_id'])
            ->from('orderrefproducts')
            ->where(['id' => $this->id])
            ->all();

        return $rows;
    }

    /**
     * change order status and add to log it
     * MySQL query INSERT INTO logoperations (operation) VALUES ($status)
     * @param integer $id
     * @param string $status ('new', 'confirmed', 'canceled', 'closed')
     * @return boolean
     */
    public static function changeStatus($id, $status)
    {
        $connection = \Yii::$app->db;
        $t = date("Y-m-d H:i:s", time());
        $result = 0;


        if(in_array($status, ['new', 'confirmed', 'canceled', 'closed']))
        {
            $command = $connection->createCommand()
                ->update('orders', [
                    'status' => $status,
                    'date_change' => $t,
                ], 'id='.$id);
            $result = $command->execute();

            if($result > 0)
            {
                $command = $connection->createCommand()
                    ->insert('logoperations', [
                        'order_id' => $id,
                        'date_add' => $t,
                        'operation' => "changed status to ".$status,
                    ]);
                $command->execute();
            }
        }

        return $result > 0;
    }

    /**
     * view history of order
     * MySQL query SELECT * FROM logoperations WHERE order_id=$this->id ORDER BY date_add DESC
     * @return array
     */
    public static function historyOrder($id)
    {
        $query = new Query();

        $rows = $query->select(['*'])
            ->from('logoperations')
            ->where(['id' => $id])
            ->orderBy(['date_add' => SORT_DESC])
            ->all();

        return $rows;
    }
}