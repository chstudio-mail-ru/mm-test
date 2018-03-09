<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * Order class for orders.
 */
class Order extends \yii\base\Object
{
    public $id;
    public $status;
    public $user_id;
    public $date_add;
    public $date_change;

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
    public static function addOrder($user_id)
    {
        $connection = \Yii::$app->db;
        $t = time();

        $command = $connection->createCommand()
                                    ->insert('orders', [
                                        'user_id' => $user_id,
                                        'date_add' => $t,
                                    ]);
        $command->execute();
        $id = $connection->getLastInsertID();

        $arr =  [
                    'id' => $id,
                    'status' => 'new',
                    'user_id' => $user_id,
                    'date_add' => $t,
                    'date_change' => null,
                ];
        return new static($arr);
    }

    /**
     * add product to order
     * MySQL query INSERT INTO orderrefproducts (order_id, product_id) VALUES ($this->id, $product_id)
     * @param  integer $product_id
     * @return boolean
     */
    public function addProduct($product_id)
    {
        $connection = \Yii::$app->db;
        $t = time();

        $command = $connection->createCommand()
            ->insert('orderrefproducts', [
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
     * remove product from order
     * MySQL query DELETE FROM orderrefproducts WHERE order_id=$this->id AND product_id=$product_id)
     * @param  integer $product_id
     * @return boolean
     */
    public function deleteProduct($product_id)
    {
        $connection = \Yii::$app->db;
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
     * change order status and add to log it
     * MySQL query INSERT INTO logoperations (operation) VALUES ($status)
     * @param string $status ('new', 'confirmed', 'canceled', 'closed')
     * @return boolean
     */
    public function changeStatus($status)
    {
        $connection = \Yii::$app->db;
        $t = time();
        $result = 0;

        if(in_array($status,  ['new', 'confirmed', 'canceled', 'closed']))
        {
            $command = $connection->createCommand()
                ->update('orders', [
                    'status' => $status,
                    'date_change' => $t,
                ], 'id='.$this->id);
            $result = $command->execute();

            if($result > 0)
            {
                $command = $connection->createCommand()
                    ->insert('logoperations', [
                        'order_id' => $this->id,
                        'date_add' => $t,
                        'operation' => "change status to ".$status,
                    ]);
                $command->execute();
            }
        }

        return $result > 0;
    }
}