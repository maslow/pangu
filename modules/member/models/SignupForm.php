<?php

namespace app\modules\member\models;

use app\base\Event;
use app\modules\member\Module;
use yii\base\ErrorException;
use yii\base\Model;

/**
 * Class SignupForm
 * @package app\modules\member\models
 */
class SignupForm extends Model
{
    public $username;
    public $password;
    public $password_confirm;

    public function rules()
    {
        return [
            [['username', 'password', 'password_confirm'], 'required'],
            [['username', 'password'], 'string', 'max' => 32, 'min' => 3],
            ['username', 'unique', 'targetClass' => User::className()],
            ['password_confirm', 'compare', 'compareAttribute' => 'password']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => \Yii::t('member', 'Username'),
            'password' => \Yii::t('member', 'Password'),
            'password_confirm' => \Yii::t('member', 'Confirm Password'),
        ];
    }

    /**
     * 验证注册信息并保存入库
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->password_hash = \Yii::$app->security->generatePasswordHash($this->password);
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->updated_at = time();
            $user->created_at = time();
            try {
                if ($user->save()) {
                    Event::trigger(Module::className(), Module::EVENT_SIGNUP_SUCCESS, new Event(['model' => $this]));
                    return true;
                } else {
                    \Yii::error($user->getErrors(), __METHOD__);
                    throw new ErrorException(\Yii::t('member', 'Throw an exception of saving data!'));
                }
            } catch (\Exception $e) {
                \Yii::error($e->getMessage(), __METHOD__);
                $this->addError('username', \Yii::t('member', 'The user has some exceptions!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_SIGNUP_FAIL, new Event(['model' => $this]));
        return false;
    }
}