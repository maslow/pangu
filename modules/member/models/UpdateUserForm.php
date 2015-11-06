<?php

namespace app\modules\member\models;

use app\modules\member\Module;
use Yii;
use yii\base\Event;
use yii\base\InvalidParamException;
use yii\base\Model;


/**
 * Class UpdateUserForm
 * @package app\modules\man\models
 */
class UpdateUserForm extends Model
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
            [['username'], 'exist', 'targetClass' => User::className()],
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
    public function update()
    {
        if ($this->validate()) {
            /* @var $user User */
            $user = User::findOne(['username' => $this->username]);
            try {
                if ($this->password) {
                    $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                }
                $user->updated_at = time();
                $user->auth_key = Yii::$app->security->generateRandomString();

                if ($user->save()) {
                    Event::trigger(Module::className(), Module::EVENT_UPDATE_USER_SUCCESS);
                    return true;
                } else {
                    Yii::error($user->getErrors(), __METHOD__);
                    throw new InvalidParamException();
                }
            } catch (\Exception $e) {
                $this->addError('username', Yii::t('member', 'Throw an exception of saving data!'));
            }
        }
        return false;
    }
}

/**
 * Class UpdateUserEvent
 * @package app\modules\member\models
 */
class UpdateUserEvent extends Event
{
    public $model;
}