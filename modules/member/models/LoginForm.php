<?php

namespace app\modules\member\models;

use app\modules\member\Module;
use app\base\Event;
use Yii;
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
            'username' => Yii::t('member', 'Username'),
            'password' => Yii::t('member', 'Password'),
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
                    Event::trigger(Module::className(), Module::EVENT_LOGIN_SUCCESS, new Event(['model' => $this]));
                    return true;
                } else {
                    $this->addError('username', Yii::t('member', 'Username and  password are incorrect!'));
                }
            } catch (\Exception $e) {
                $this->addError('username', Yii::t('member', 'The user has some exceptions!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_LOGIN_FAIL, new Event(['model' => $this]));
        return false;
    }
}