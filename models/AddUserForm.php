<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RegisterForm is the model behind the register form.
 */
class AddUserForm extends Model
{
    public $name;
    public $email;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name'  => 'Имя',
            'email'  => 'E-mail',
        ];
    }

    /**
     * Insert new user in to tbl_user and sends e-mail to adminEmail.
     * @return User
     */
    public function add()
    {
        if ($this->validate()) {
            $user = User::addUser($this->name, $this->email);

            return $user;
        }
    }
}
