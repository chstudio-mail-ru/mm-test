<?php

namespace app\models;

use yii\db\Query;

/**
 * HistoryOrder class for working with log operations.
 */
class HistoryOrder
{
    /**
     * list operations
     * MySQL query SELECT * FROM logoperations WHERE order_id=$order_id ORDER BY date_add DESC
     * @param integer $order_id
     * @return array
     */
    public static function listOperations($order_id)
    {
        $query = new Query();

        $rows = $query->select(['*'])
            ->from('logoperations')
            ->where(['order_id' => $order_id])
            ->orderBy(['date_add' => SORT_DESC])
            ->all();

        return $rows;
    }
}