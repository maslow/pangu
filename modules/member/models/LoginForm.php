<?php

namespace app\modules\member\models;

use app\modules\member\Module;
use Yii;
use yii\base\Event;
use yii\base\Model;

/**
 * Class LoginForm
 * @package app\modules\member\models
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
            [['username'], 'exist', 'targetClass' => User::className()]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }

    /**
     * 验证登录表单，并执行登录操作
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            /* @var User $user */
            $user = User::findOne(['username' => $this->username]);
            try {
                if ($user && Yii::$app->security->validatePassword($this->password, $user->password_hash)) {
                    Yii::$app->user->login($user);
                    Event::trigger(Module::className(),Module::EVENT_LOGIN_SUCCESS,new LoginEvent(['model'=>$this]));
                    return true;
                } else {
                    $this->addError('username', '用户名密码不匹配');
                }
            } catch (\Exception $e) {
                $this->addError('username', '该用户异常!');
            }
        }
        Event::trigger(Module::className(),Module::EVENT_LOGIN_FAIL,new LoginEvent(['model'=>$this]));
        return false;
    }
}

/**
 * Class LoginEvent
 * @package app\modules\member\models
 */
class LoginEvent extends Event{
    public $model;
}