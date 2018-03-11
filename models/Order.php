<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\base\Model;

/**
 * Order class for orders.
 */
class Order extends Model
{
    public $id;
    public $status;
    public $user_id;
    public $user_name;
    public $date_add;
    public $date_change;
    public $f_status;
    public $f_user_id;
    public $f_sum_min;
    public $f_sum_max;
    public $sum;
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
     *
     * @return array
     */
    public function listOrders()
    {
        $query = new Query();

        $query->select(['orders.*', 'SUM(products.price) AS sum', 'users.name AS user_name'])
            ->from('orders')
            ->leftJoin('orderrefproducts', "orders.id=orderrefproducts.order_id")
            ->leftJoin('products', "orderrefproducts.product_id=products.id")
            ->leftJoin('users', "orders.user_id=users.id");

        if(!empty($this->f_status) && $this->f_status != 'all')
            $query->andFilterCompare('orders.status', $this->f_status);
        if(!empty($this->f_user_id) && $this->f_user_id != 0)
            $query->andFilterCompare('orders.user_id', $this->f_user_id);

        $query->groupBy('orderrefproducts.order_id');

        if(!empty($this->f_sum_min) && !empty($this->f_sum_max))
        {
            $query->having(['>=', 'sum',  $this->f_sum_min]);
            $query->andHaving(['<=', 'sum', $this->f_sum_max]);
        }
        elseif(!empty($this->f_sum_min))
            $query->having(['>=', 'sum', $this->f_sum_min]);
        elseif(!empty($this->f_sum_max))
            $query->having(['<=', 'sum', $this->f_sum_max]);
        $rows = $query->orderBy(['orders.date_add' => SORT_DESC])
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

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['f_status', 'f_user_id', 'f_sum_min', 'f_sum_max'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'f_status' => 'Статус',
            'f_user_id' => 'Покупатель',
            'f_sum_min' => 'Минимальная сумма заказа',
            'f_sum_max' => 'Максимальная сумма заказа',
        ];
    }
}