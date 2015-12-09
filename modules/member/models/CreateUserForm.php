<?php

namespace app\modules\member\models;

use app\modules\member\Module;
use app\base\Event;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;


/**
 * Class CreateUserForm
 * @package app\modules\backend\models
 */
class CreateUserForm extends Model
{
    public $username;
    public $password;
    public $password_confirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'password_confirm'], 'required'],
            [['username', 'password'], 'string', 'min' => 3, 'max' => 32],
            [['username'], 'unique', 'targetClass' => User::className()],
            ['password_confirm', 'compare', 'compareAttribute' => 'password']
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
            'password_confirm' => Yii::t('member', 'Confirm Password'),
        ];
    }

    /**
     * 验证表单，并执行保存操作
     * @return bool
     */
    public function create()
    {
        if ($this->validate()) {
            $user = new User();
            try {
                $user->username = $this->username;
                $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                $user->updated_at = time();
                $user->created_at = time();
                $user->auth_key = Yii::$app->security->generateRandomString();

                if ($user->save()) {
                    Event::trigger(Module::className(), Module::EVENT_CREATE_USER_SUCCESS, new Event(['model' => $this]));
                    return true;
                } else {
                    Yii::error($user->getErrors(), __METHOD__);
                    throw new InvalidParamException();
                }
            } catch (\Exception $e) {
                $this->addError('username', Yii::t('member', 'Throw an exception of saving data!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_CREATE_USER_FAIL, new Event(['model' => $this]));
        return false;
    }
}