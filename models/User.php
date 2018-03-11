<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * User class for athenticated users.
 */
class User extends \yii\base\Object
{
    public $id;
    public $name;
    public $email;
    public $date_add;

    public static function findIdentity($id)
    {
        $query = new Query();

        $row = $query->select(['*'])
                     ->from('users')
                     ->where(['id' => intval($id)])
                     ->one();

        
        return isset($row['id'])? new static($row) : null;
    }

    /**
     * @inheritdoc
     * take user $id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * add new user to DB
     * MySQL query INSERT INTO users (name, email) VALUES ($username, $useremail)
     * @param  string $useremail
     * @param  string $username
     * @return static|null
     */
    public static function addUser($username, $useremail)
    {
        $connection = \Yii::$app->db;
        $t = time();
        $command = $connection->createCommand()
                                    ->insert('users', [
                                        'name' => $username,
                                        'email' => $useremail,
                                        'date_add' => $t,
                                    ]);
        $command->execute();
        $id = $connection->getLastInsertID();

        $arr =  [
                    'id' => $id,
                    'name' => $username,
                    'email' => $useremail,
                    'date_add' => $t,
                ];
        return new static($arr);
    }

    /**
     * list users
     * MySQL query SELECT * FROM users ORDER BY date_add DESC
     * @return array
     */
    public function listUsers()
    {
        $query = new Query();

        $rows = $query->select(['*'])
            ->from('users')
            ->orderBy(['date_add' => SORT_DESC])
            ->all();

        return $rows;
    }

    /**
     * save user to DB
     */
    public function saveUser()
    {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand()
                                    ->update('users', [
                                        'name' => $this->name,
                                        'email' => $this->email,
                                    ], 'id='.$this->id);
        $command->execute();
    }
}