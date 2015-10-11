<?php

namespace app\modules\member\models;

use Yii;
use yii\base\Model;

/**
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username','password'], 'string', 'min'=>3,'max' => 32],
            [['username'], 'exist','targetClass'=>User::className()]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
        ];
    }

    public function login(){
        return true;
    }
}
