<?php

namespace app\modules\man\models;

use app\modules\man\Module;
use Yii;
use yii\base\Event;
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
        $event = new LoginEvent();
        $event->form = $this;
        if ($this->validate()) {
            /* @var Manager $manager */
            $manager = Manager::findOne(['username' => $this->username]);
            try {
                if ($manager && Yii::$app->security->validatePassword($this->password, $manager->password_hash)) {
                    $this->getManager()->login($manager);
                    Event::trigger(Module::className(),Module::EVENT_LOGIN_SUCCESS,$event);
                    return true;
                } else {
                    $this->addError('username', '用户名密码不匹配');
                }
            } catch (\Exception $e) {
                Yii::error($e->getMessage());
                $this->addError('username', '该用户异常!');
            }
        }
        Event::trigger(Module::className(),Module::EVENT_LOGIN_FAIL,$event);
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

/**
 * Class LoginEvent
 * @package app\modules\man\models
 */
class LoginEvent extends Event{
    public $form;
}
