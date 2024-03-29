<?php

namespace app\modules\backend\models;

use app\modules\backend\Module;
use Yii;
use app\base\Event;
use yii\base\InvalidParamException;
use yii\base\Model;

/**
 * Class ResetPasswordForm
 * @package app\modules\backend\models
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $password_confirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'password_confirm'], 'required'],
            ['password', 'string', 'min' => 6, 'max' => 32],
            ['password_confirm', 'compare', 'compareAttribute' => 'password']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('backend', 'Password'),
            'password_confirm' => Yii::t('backend', 'Confirm Password'),
        ];
    }

    /**
     * 验证表单，并执行保存操作
     * @return bool
     */
    public function save()
    {
        $event = new Event(['model' => $this]);
        if ($this->validate()) {
            /* @var $manager Manager */
            $manager = Yii::$app->manager->identity;
            try {
                $manager->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                $manager->updated_at = time();
                $manager->auth_key = Yii::$app->security->generateRandomString();

                if ($manager->save()) {
                    Event::trigger(Module::className(), Module::EVENT_RESET_PASSWORD_SUCCESS, $event);
                    return true;
                } else {
                    throw new InvalidParamException();
                }
            } catch (\Exception $e) {
                Yii::error($manager->getErrors());
                Yii::error($e->getMessage());
                $this->addError('password', Yii::t('backend', 'Throw an exception of saving data!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_RESET_PASSWORD_FAIL, $event);
        return false;
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth()
    {
        return Yii::$app->authManager;
    }
}