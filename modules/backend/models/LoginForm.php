<?php

namespace app\modules\backend\models;

use app\modules\backend\Module;
use Yii;
use app\base\Event;
use yii\base\Model;

/**
 * Class LoginForm
 * @package app\modules\backend\models
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string', 'min' => 3, 'max' => 32],
            [['username'], 'exist', 'targetClass' => Manager::className()],
            ['verifyCode', 'captcha']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('backend', 'Username'),
            'password' => Yii::t('backend', 'Password'),
            'verifyCode' => Yii::t('backend', 'Captcha'),
        ];
    }

    /**
     * 验证登录表单，并执行登录操作
     * @return bool
     */
    public function login()
    {
        $event = new Event(['model' => $this]);
        if ($this->validate()) {
            /* @var Manager $manager */
            $manager = Manager::findOne(['username' => $this->username]);
            try {
                if ($manager && Yii::$app->security->validatePassword($this->password, $manager->password_hash)) {
                    $this->getManager()->login($manager);
                    Event::trigger(Module::className(), Module::EVENT_LOGIN_SUCCESS, $event);
                    return true;
                } else {
                    $this->addError('username', Yii::t('backend','Username and  password are incorrect!'));
                }
            } catch (\Exception $e) {
                Yii::error($e->getMessage());
                $this->addError('username', Yii::t('backend','The user has some exceptions!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_LOGIN_FAIL, $event);
        return false;
    }

    /**
     * 获取管理员(manager)组件对象
     * @return \yii\web\User
     */
    protected function getManager()
    {
        return Yii::$app->manager;
    }
}
