<?php

namespace app\modules\man\models;

use app\modules\man\Module;
use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 * @package app\modules\man\models
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
            [['username', 'password'], 'string', 'min' => 3, 'max' => 32],
            [['username'], 'exist', 'targetClass' => Manager::className()]
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

    /**
     * 验证登录表单，并执行登录操作
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            /* @var Manager $manager */
            $manager = Manager::findOne(['username' => $this->username]);
            try {
                if ($manager && Yii::$app->security->validatePassword($this->password, $manager->password_hash)) {
                    $this->getManager()->login($manager);
                    return true;
                } else {
                    $this->addError('username', '用户名密码不匹配');
                    return false;
                }
            } catch (\Exception $e) {
                $this->addError('username', '该用户异常!');
                return false;
            }
        }
        return false;
    }

    /**
     * 获取管理员(manager)组件对象
     * @return \yii\web\User
     */
    protected function getManager(){
        return Yii::$app->manager;
    }
}
